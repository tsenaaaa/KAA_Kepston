<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Destinasi as DestinasiModel;
use App\Services\TikTokService;

class DestinasiController extends BaseController
{
    private TikTokService $tikTokService;

    public function __construct(TikTokService $tikTokService)
    {
        $this->tikTokService = $tikTokService;
    }

    // offset to avoid id collision with API-provided items
    private const DB_ID_OFFSET = 100000;

    private function getData()
    {
        // DB data (live) - only admin-added data
        $dbItems = DestinasiModel::latest()->get()->map(function ($model) {
            return [
                'id' => self::DB_ID_OFFSET + $model->id,
                'nama' => $model->nama,
                'foto' => $model->foto,
                'deskripsi' => $model->deskripsi,
                'alamat' => $model->alamat,
                'tiktok' => $model->tiktok,
                'category' => $model->kategori ?? 'wisata',
                'rating' => $model->rating ?? 0,
                'reviews_count' => $model->reviews_count ?? 0,
                'reviews' => [],
                'photos' => []
                ,
                'koordinat' => [
                    'lat' => $model->latitude ? (float) $model->latitude : null,
                    'lng' => $model->longitude ? (float) $model->longitude : null,
                ]
            ];
        });

        return collect($dbItems)->values();
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
        $categoryNames = [
            'Culinary' => 'Culinary',
            'Tourism' => 'Tourism',
            'Shopping' => 'Shopping'
        ];

        $displayName = $categoryNames[$kategori] ?? ucfirst($kategori);

        $filtered = $this->getData()
            ->filter(fn($item) => $item['category'] === $kategori)
            ->values();

        return view('destinasi.index', [
            'kategori' => $displayName,
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

        // Fetch TikTok comments if TikTok URL exists
        $comments = [];
        if (!empty($item['tiktok'])) {
            $comments = $this->tikTokService->getComments($item['tiktok']);
        }

        // Fallback to dummy comments if no TikTok comments
        if (empty($comments)) {
            $comments = [
                ['user' => 'andi', 'text' => 'Tempatnya asik!', 'likes' => 15, 'timestamp' => now()->subHours(2)->toISOString()],
                ['user' => 'sinta', 'text' => 'Makanannya enak dan murah.', 'likes' => 8, 'timestamp' => now()->subHours(4)->toISOString()],
                ['user' => 'riko', 'text' => 'Spot foto cakep di sini.', 'likes' => 22, 'timestamp' => now()->subHours(6)->toISOString()]
            ];
        }

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

        // Fetch TikTok comments
        $comments = [];
        if (!empty($tiktokUrl)) {
            $comments = $this->tikTokService->getComments($tiktokUrl);
        }

        // Fallback to dummy comments if no TikTok comments
        if (empty($comments)) {
            $comments = [
                ['user' => 'andi', 'text' => 'Tempatnya asik!', 'likes' => 15, 'timestamp' => now()->subHours(2)->toISOString()],
                ['user' => 'sinta', 'text' => 'Makanannya enak dan murah.', 'likes' => 8, 'timestamp' => now()->subHours(4)->toISOString()],
                ['user' => 'riko', 'text' => 'Spot foto cakep di sini.', 'likes' => 22, 'timestamp' => now()->subHours(6)->toISOString()]
            ];
        }

        return view('destinasi.tiktok', [
            'data' => $item,
            'comments' => $comments,
            'videoId' => $videoId
        ]);
    }
}
