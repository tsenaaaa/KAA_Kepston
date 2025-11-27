<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DestinasiController extends BaseController
{
    private $data = [
        [
            "id" => 1,
            "nama" => "Warung Kopi Pojok KAA",
            "foto" => "/mnt/data/1359556c-25ae-4ca4-b82b-7f3bfa4ff98b.png",
            "deskripsi" => "Kuliner dekat Museum KAA dengan harga terjangkau.",
            "tiktok" => "https://www.tiktok.com/@coidiscover/video/7437154225852894520",
            "category" => "kuliner"
        ],
        [
            "id" => 2,
            "nama" => "Cafe Asia Afrika",
            "foto" => "/mnt/data/2fafb742-f87b-4e63-b0e4-e3726bbe4013.png",
            "deskripsi" => "Tempat nongkrong cozy dekat Museum KAA.",
            "tiktok" => "https://www.tiktok.com/@jalanjalanjajananma/video/7542895044546546949",
            "category" => "penginapan"
        ],
        [
            "id" => 3,
            "nama" => "Taman Sejarah KAA",
            "foto" => "/mnt/data/28a83c79-eb9f-4589-bed2-abc9b8c9502d.png",
            "deskripsi" => "Taman kecil untuk bersantai dan spot foto bersejarah.",
            "tiktok" => "https://www.tiktok.com/@kitadibandung/video/7393176755684510981",
            "category" => "wisata"
        ],
        [
            "id" => 4,
            "nama" => "Belanja di Sekitar KAA",
            "foto" => "/mnt/data/placeholder.jpg",
            "deskripsi" => "Tempat belanja menarik di sekitar Museum KAA.",
            "tiktok" => "https://www.tiktok.com/@nona.eats/video/7503477496512351494",
            "category" => "belanja"
        ],
    ];

    public function index(Request $request)
    {
        if ($request->has('q')) {
            return $this->search($request);
        }

        return view('destinasi.index', [
            'kategori' => 'Semua',
            'list' => collect($this->data)
        ]);
    }

    public function kategori($kategori)
    {
        $filtered = collect($this->data)
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

        $filtered = collect($this->data)->filter(function ($item) use ($keyword) {
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
        $item = collect($this->data)->firstWhere('id', (int) $id);

        if (!$item) abort(404);

        // dummy comments
        $comments = [
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
        $item = collect($this->data)->firstWhere('id', (int) $id);

        if (!$item) abort(404);

        // Extract video ID from TikTok URL
        $tiktokUrl = $item['tiktok'];
        $videoId = null;
        if (preg_match('/\/video\/(\d+)/', $tiktokUrl, $matches)) {
            $videoId = $matches[1];
        }

        // dummy comments
        $comments = [
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
