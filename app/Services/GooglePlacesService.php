<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GooglePlacesService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_PLACES_API_KEY');
    }

    public function getPlaceDetails($placeId)
    {
        if (!$this->apiKey || $this->apiKey === 'your_api_key_here') {
            return null;
        }

        try {
            return Cache::remember("place_{$placeId}", 3600, function () use ($placeId) {
                $url = "https://maps.googleapis.com/maps/api/place/details/json";

                $response = Http::timeout(10)->get($url, [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,reviews,photos,formatted_address',
                    'key' => $this->apiKey
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['status']) && $data['status'] === 'OK') {
                        return $this->formatPlaceData($data['result']);
                    }
                }

                return null;
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatPlaceData($placeData)
    {
        $photos = [];
        if (isset($placeData['photos']) && is_array($placeData['photos'])) {
            foreach (array_slice($placeData['photos'], 0, 5) as $photo) {
                $photos[] = [
                    'url' => "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$photo['photo_reference']}&key={$this->apiKey}",
                    'width' => $photo['width'] ?? 400,
                    'height' => $photo['height'] ?? 400
                ];
            }
        }

        $reviews = [];
        if (isset($placeData['reviews']) && is_array($placeData['reviews'])) {
            foreach (array_slice($placeData['reviews'], 0, 3) as $review) {
                $reviews[] = [
                    'author_name' => $review['author_name'] ?? '',
                    'rating' => $review['rating'] ?? 0,
                    'text' => $review['text'] ?? '',
                    'time' => isset($review['time']) ? date('Y-m-d', $review['time']) : '',
                    'profile_photo_url' => $review['profile_photo_url'] ?? ''
                ];
            }
        }

        return [
            'name' => $placeData['name'] ?? '',
            'rating' => $placeData['rating'] ?? 0,
            'photos' => $photos,
            'reviews' => $reviews,
            'address' => $placeData['formatted_address'] ?? ''
        ];
    }
}
