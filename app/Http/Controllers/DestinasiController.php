<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Services\GooglePlacesService;
use Illuminate\Support\Facades\Cache;
use App\Models\Destinasi as DestinasiModel;

class DestinasiController extends BaseController
{
    private $googlePlacesService;
    private $placeIds = [
        'ChIJE1wx6yTmaC4R0Rr5BuyFfwk', // Warung Kopi Purnama
        'ChIJMcIOvp3oaC4RZdAMyaoSdBc', // Alun-alun Kota Bandung
        'ChIJ_eP3oiXmaC4RF-TYVUiHG1E', // Sate Padang Pak Datuk
        'ChIJaWgGXSzmaC4RhoPRV3eWoIQ', // Mie Naripan
        'ChIJHREpHC_maC4RFhuMcU-xCU8'  // Hotel Savoy Homann Bandung
    ];

    public function __construct(GooglePlacesService $googlePlacesService)
    {
        $this->googlePlacesService = $googlePlacesService;
    }

    // offset to avoid id collision with API-provided items
    private const DB_ID_OFFSET = 100000;

    private function getData()
{
    // API data (cached)
    $apiCollection = Cache::remember('destinasi_api_data', 3600, function () {
        $data = [];

        foreach ($this->placeIds as $index => $placeId) {
            $placeData = $this->googlePlacesService->getPlaceDetails($placeId);

            if ($placeData) {
                $data[] = [
                    "id" => $index + 1,
                    "nama" => $placeData['name'],
                    "foto" => $placeData['photos'][0]['url'] ?? '/storage/placeholder.jpg',
                    "deskripsi" => $placeData['address'],
                    "tiktok" => "",
                    "category" => $this->getCategoryFromName($placeData['name']),
                        "rating" => $placeData['rating'] ?? 0,
                        "reviews" => $placeData['reviews'] ?? [],
                        "reviews_count" => is_array($placeData['reviews']) ? count($placeData['reviews']) : 0,
                    "photos" => $placeData['photos'] ?? []
                ];
            }
        }

        return collect($data)->isEmpty()
            ? $this->getFallbackData()
            : collect($data);
    });

    // DB data (live)
    $dbItems = DestinasiModel::latest()->get()->map(function ($model) {
        return [
            'id' => self::DB_ID_OFFSET + $model->id,
            'nama' => $model->nama,
            'foto' => $model->foto ?: '/storage/placeholder.jpg',
            'deskripsi' => $model->deskripsi,
            'alamat' => $model->alamat,
            'tiktok' => $model->tiktok,
            'category' => $model->kategori ?? 'wisata',
            'rating' => $model->rating ?? 0,
                'reviews' => [],
                'reviews_count' => is_array($model->meta) && isset($model->meta['reviews_count']) ? intval($model->meta['reviews_count']) : 0,
            'photos' => []
            ,
            'koordinat' => [
                'lat' => $model->latitude ? (float) $model->latitude : null,
                'lng' => $model->longitude ? (float) $model->longitude : null,
            ]
        ];
    });

    return collect($dbItems)->merge(collect($apiCollection))->values();
}


    private function getCategoryFromName($name)
    {
        $name = strtolower($name);

        if (str_contains($name, 'coffee') || str_contains($name, 'cafe') || str_contains($name, 'warung') || str_contains($name, 'mie')) {
            return 'kuliner';
        } elseif (str_contains($name, 'hotel') || str_contains($name, 'artotel')) {
            return 'penginapan';
        } elseif (str_contains($name, 'alun-alun') || str_contains($name, 'taman')) {
            return 'wisata';
        } else {
            return 'wisata'; // default
        }
    }

    private function getFallbackData()
    {
        return collect([
            [
                "id" => 1,
                "nama" => "Warung Kopi Pojok KAA",
                "foto" => "/mnt/data/1359556c-25ae-4ca4-b82b-7f3bfa4ff98b.png",
                "deskripsi" => "Kuliner dekat Museum KAA dengan harga terjangkau.",
                "tiktok" => "https://www.tiktok.com/@coidiscover/video/7437154225852894520",
                "category" => "kuliner",
                "rating" => 4.5,
                "reviews" => [],
                "photos" => []
            ],
            [
                "id" => 2,
                "nama" => "Cafe Asia Afrika",
                "foto" => "/mnt/data/2fafb742-f87b-4e63-b0e4-e3726bbe4013.png",
                "deskripsi" => "Tempat nongkrong cozy dekat Museum KAA.",
                "tiktok" => "https://www.tiktok.com/@jalanjalanjajananma/video/7542895044546546949",
                "category" => "penginapan",
                "rating" => 4.2,
                "reviews" => [],
                "photos" => []
            ],
            [
                "id" => 3,
                "nama" => "Taman Sejarah KAA",
                "foto" => "/mnt/data/28a83c79-eb9f-4589-bed2-abc9b8c9502d.png",
                "deskripsi" => "Taman kecil untuk bersantai dan spot foto bersejarah.",
                "tiktok" => "https://www.tiktok.com/@kitadibandung/video/7393176755684510981",
                "category" => "wisata",
                "rating" => 4.0,
                "reviews" => [],
                "photos" => []
            ],
            [
                "id" => 4,
                "nama" => "Belanja di Sekitar KAA",
                "foto" => "/mnt/data/placeholder.jpg",
                "deskripsi" => "Tempat belanja menarik di sekitar Museum KAA.",
                "tiktok" => "https://www.tiktok.com/@nona.eats/video/7503477496512351494",
                "category" => "belanja",
                "rating" => 3.8,
                "reviews" => [],
                "photos" => []
            ],
        ]);
    }

    public function index(Request $request)
    {
        if ($request->has('q')) {
            return $this->search($request);
        }

        return view('destinasi.index', [
            'kategori' => 'Semua',
            'list' => $this->getData()
        ]);
    }

    public function kategori($kategori)
    {
        $filtered = $this->getData()
            ->filter(fn($item) => strtolower($item['category']) === strtolower($kategori))
            ->values();

        return view('destinasi.index', [
            'kategori' => ucfirst($kategori),
            'list' => $filtered
        ]);
    }

    public function search(Request $request)
    {
        $keyword = trim(strtolower($request->query('q', '')));

        $filtered = $this->getData()->filter(function ($item) use ($keyword) {
            if ($keyword === '') return true;
            return str_contains(strtolower($item['nama']), $keyword)
                || str_contains(strtolower($item['deskripsi']), $keyword);
        })->values();

        return view('destinasi.index', [
            'kategori' => 'Hasil Pencarian',
            'list' => $filtered
        ]);
    }

    public function show($id)
    {
        $item = $this->getData()->firstWhere('id', (int) $id);

        if (!$item) abort(404);

        // Use Google reviews if available, otherwise dummy comments
        $comments = !empty($item['reviews']) ? $item['reviews'] : [
            ['user' => 'andi', 'text' => 'Tempatnya asik!'],
            ['user' => 'sinta', 'text' => 'Makanannya enak dan murah.'],
            ['user' => 'riko', 'text' => 'Spot foto cakep di sini.']
        ];

        return view('destinasi.show', [
            'data' => $item,
            'comments' => $comments
        ]);
    }

    public function tiktok($id)
    {
        $item = $this->getData()->firstWhere('id', (int) $id);

        if (!$item) abort(404);

        // Extract video ID from TikTok URL
        $tiktokUrl = $item['tiktok'];
        $videoId = null;
        if (preg_match('/\/video\/(\d+)/', $tiktokUrl, $matches)) {
            $videoId = $matches[1];
        }

        // Use Google reviews if available, otherwise dummy comments
        $comments = !empty($item['reviews']) ? $item['reviews'] : [
            ['user' => 'andi', 'text' => 'Tempatnya asik!'],
            ['user' => 'sinta', 'text' => 'Makanannya enak dan murah.'],
            ['user' => 'riko', 'text' => 'Spot foto cakep di sini.']
        ];

        return view('destinasi.tiktok', [
            'data' => $item,
            'comments' => $comments,
            'videoId' => $videoId
        ]);
    }
}
