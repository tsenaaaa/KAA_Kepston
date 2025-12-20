<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekomendasi Destinasi - Museum KAA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .navbar-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: #dc2626;
            font-size: 18px;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .nav-link {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #2563eb;
        }
        .nav-link.active {
            color: white;
            background-color: #2563eb;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .breadcrumb-section {
            background: #f9fafb;
            padding: 24px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .breadcrumb-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .breadcrumb {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            color: #111827;
        }
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #111827;
        }
        .main-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        .controls-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 24px;
        }
        .category-select {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            width: 200px;
        }
        .search-form {
            display: flex;
            gap: 0;
        }
        .search-form input {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px 0 0 6px;
            width: 320px;
            font-size: 14px;
        }
        .search-form button {
            background: #dc2626;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }
        .search-form button:hover {
            background: #b91c1c;
        }
        .title-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }
        .tiktok-section {
            margin-bottom: 48px;
        }
        .tiktok-section .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }
        .tiktok-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }
        .tiktok-row::-webkit-scrollbar {
            height: 6px;
        }
        .tiktok-row::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .tiktok-row::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        .tiktok-row::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        .tiktok-card {
            flex: 0 0 200px;
            background: transparent;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: none;
            transition: transform 0.2s ease;
            cursor: pointer;
        }
        .tiktok-card:hover {
            transform: scale(1.05);
        }
        .tiktok-video-wrapper {
            position: relative;
            width: 100%;
            height: 300px;
            background: #f3f4f6;
        }
        .tiktok-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .tiktok-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e5e7eb;
            color: #6b7280;
            font-size: 13px;
            text-align: center;
            padding: 16px;
        }
        .tiktok-info {
            padding: 12px;
        }
        .tiktok-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .tiktok-desc {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        .tiktok-link {
            display: inline-block;
            font-size: 12px;
            color: #dc2626;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .tiktok-link:hover {
            color: #b91c1c;
        }
        .cards-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 24px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }
        .cards-grid::-webkit-scrollbar {
            height: 6px;
        }
        .cards-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .cards-grid::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        .cards-grid::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        .card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            flex: 0 0 300px;
        }
        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }
        .card-image {
            width: 100%;
            height: 160px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 13px;
            overflow: hidden;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
            text-align: center;
        }

        .card-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 16px;
            flex: 1;
        }
        .card-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .rating-stars {
            display: flex;
            gap: 2px;
        }
        .star {
            color: #ddd;
            font-size: 14px;
        }
        .star.filled {
            color: #fbbf24;
        }
        .rating-text {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }
        .card-reviews {
            margin-bottom: 12px;
        }
        .reviews-count {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            text-align: center;
        }
        .latest-review {
            font-size: 13px;
            color: #4b5563;
            font-style: italic;
            margin: 0;
            text-align: center;
            line-height: 1.4;
        }
        .card-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-primary {
            flex: 1;
            background: #dc2626;
            color: white;
            padding: 10px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background: #b91c1c;
        }
        .btn-secondary {
            flex: 1;
            background: white;
            color: #111827;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-secondary:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }
        .empty-state {
            background: white;
            padding: 60px 20px;
            border-radius: 8px;
            text-align: center;
            color: #6b7280;
            font-size: 16px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo-section">
                🏛️ Museum KAA
            </div>
            <div class="nav-menu">
                <a href="#" class="nav-link">Beranda</a>
                <a href="{{ route('destinasi.index') }}" class="nav-link active">Destinasi Sekitar</a>
                <a href="#" class="nav-link">Berita</a>
                <a href="#" class="nav-link">Reservasi</a>
                <a href="#" class="nav-link">Struktur Organisasi</a>
                <a href="#" class="nav-link">Virtual Museum</a>
                @auth
                    <a href="{{ route('admin.destinasi.index') }}" class="nav-link">Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="breadcrumb-content">
            <div class="breadcrumb">
                <a href="#">Beranda</a> <span class="mx-1">/</span> <span>Rekomendasi Destinasi</span>
            </div>
            <h1 class="page-title">Rekomendasi Destinasi</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Controls -->
        <div class="controls-section">
            <select onchange="if(this.value) window.location.href=this.value" class="category-select">
                <option value="{{ route('destinasi.index') }}" {{ $kategori === 'Semua' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="{{ route('destinasi.kategori', 'kuliner') }}" {{ strtolower($kategori) === 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                <option value="{{ route('destinasi.kategori', 'wisata') }}" {{ strtolower($kategori) === 'wisata' ? 'selected' : '' }}>Wisata</option>
                <option value="{{ route('destinasi.kategori', 'belanja') }}" {{ strtolower($kategori) === 'belanja' ? 'selected' : '' }}>Belanja</option>
            </select>

            <form action="{{ route('destinasi.search') }}" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari destinasi..." value="{{ request()->q ?? '' }}">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Title -->
        <div class="title-section">
            <h2 class="section-title">Rekomendasi ({{ $kategori }}) Sekitar Museum KAA</h2>
        </div>

        <!-- TikTok Videos -->
        @php
            $tiktokItems = collect($list)->filter(fn($item) => !empty($item['tiktok']))->values();
        @endphp
        @if($tiktokItems->count() > 0)
            <div class="tiktok-section">
                <h3 class="section-title">Video TikTok Destinasi</h3>
                <div class="tiktok-row">
                    @foreach($tiktokItems as $item)
                        <div class="tiktok-card">
                            <div class="tiktok-video-wrapper">
                                @php
                                    $videoId = null;
                                    if (preg_match('/\/video\/(\d+)/', $item['tiktok'], $matches)) {
                                        $videoId = $matches[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe
                                        src="https://www.tiktok.com/embed/v2/{{ $videoId }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        class="tiktok-iframe"
                                    ></iframe>
                                @else
                                    <div class="tiktok-placeholder">
                                        <span>{{ $item['nama'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Cards Grid -->
        <div class="cards-grid">
            @forelse($list as $item)
                <div class="card">
                    <div class="card-image">
                        @if(!empty($item['foto']))
                            <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}">
                        @else
                            <span>No Image</span>
                        @endif
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ $item['nama'] }}</h3>

                        @if(isset($item['rating']) && $item['rating'] > 0)
                            <div class="card-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= round($item['rating']) ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                                <span class="rating-text">{{ number_format($item['rating'], 1) }}</span>
                            </div>
                        @endif

                        @if(isset($item['reviews']) && count($item['reviews']) > 0)
                            <div class="card-reviews">
                                <div class="reviews-count">{{ count($item['reviews']) }} ulasan</div>
                                @if(isset($item['reviews'][0]['text']) && !empty($item['reviews'][0]['text']))
                                    <p class="latest-review">"{{ Str::limit($item['reviews'][0]['text'], 80) }}"</p>
                                @endif
                            </div>
                        @endif

                        <p class="card-description">{{ $item['deskripsi'] }}</p>
                        <div class="card-buttons">
                            <a href="{{ route('destinasi.show', $item['id']) }}" class="btn-primary">Lihat Selengkapnya</a>
                            <a href="{{ $item['tiktok'] }}" target="_blank" class="btn-secondary"> Video</a>

                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    Tidak ada destinasi sesuai filter / pencarian.
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>