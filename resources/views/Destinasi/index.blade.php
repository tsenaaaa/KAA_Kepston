@extends('layouts.public')

@section('title', 'Rekomendasi Destinasi - Museum KAA')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('styles')
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

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .main-content {
                padding: 32px 20px;
            }
            .controls-section {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }
            .category-select {
                width: 100%;
            }
            .search-form input {
                width: 100%;
            }
            .cards-grid {
                gap: 20px;
            }
            .card {
                flex: 0 0 280px;
            }
        }

        @media (max-width: 768px) {
            .breadcrumb-section {
                padding: 20px 0;
            }
            .breadcrumb-content {
                padding: 0 20px;
            }
            .page-title {
                font-size: 24px;
            }
            .main-content {
                padding: 24px 16px;
            }
            .controls-section {
                margin-bottom: 32px;
            }
            .title-section {
                margin-bottom: 32px;
            }
            .section-title {
                font-size: 20px;
            }
            .tiktok-section .section-title {
                font-size: 18px;
            }
            .tiktok-card {
                flex: 0 0 180px;
            }
            .tiktok-video-wrapper {
                height: 250px;
            }
            .cards-grid {
                gap: 16px;
            }
            .card {
                flex: 0 0 260px;
            }
            .card-content {
                padding: 16px;
            }
            .card-title {
                font-size: 15px;
                margin-bottom: 10px;
            }
            .card-description {
                font-size: 12px;
                margin-bottom: 14px;
            }
            .card-buttons {
                gap: 8px;
            }
            .btn-primary,
            .btn-secondary {
                padding: 8px 10px;
                font-size: 12px;
            }
            .map-section {
                margin-top: 32px;
            }
            .map-section h3 {
                font-size: 18px;
                margin-bottom: 16px;
            }
            #map {
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-section {
                padding: 16px 0;
            }
            .breadcrumb-content {
                padding: 0 16px;
            }
            .breadcrumb {
                font-size: 12px;
                margin-bottom: 8px;
            }
            .page-title {
                font-size: 20px;
            }
            .main-content {
                padding: 20px 12px;
            }
            .title-section {
                margin-bottom: 24px;
            }
            .section-title {
                font-size: 18px;
            }
            .tiktok-section {
                margin-bottom: 32px;
            }
            .tiktok-section .section-title {
                font-size: 16px;
            }
            .tiktok-card {
                flex: 0 0 160px;
            }
            .tiktok-video-wrapper {
                height: 200px;
            }
            .tiktok-info {
                padding: 8px;
            }
            .tiktok-title {
                font-size: 13px;
            }
            .tiktok-desc {
                font-size: 11px;
            }
            .tiktok-link {
                font-size: 11px;
            }
            .cards-grid {
                gap: 12px;
            }
            .card {
                flex: 0 0 240px;
            }
            .card-image {
                height: 140px;
            }
            .card-content {
                padding: 12px;
            }
            .card-title {
                font-size: 14px;
                margin-bottom: 8px;
            }
            .card-rating {
                margin-bottom: 6px;
            }
            .rating-stars .star {
                font-size: 12px;
            }
            .rating-text {
                font-size: 12px;
            }
            .card-description {
                font-size: 11px;
                margin-bottom: 12px;
                line-height: 1.5;
            }
            .card-buttons {
                flex-direction: column;
                gap: 6px;
            }
            .btn-primary,
            .btn-secondary {
                padding: 8px 12px;
                font-size: 12px;
            }
            .empty-state {
                padding: 40px 16px;
                font-size: 14px;
            }
            .map-section {
                margin-top: 24px;
            }
            .map-section h3 {
                font-size: 16px;
                margin-bottom: 12px;
            }
            #map {
                height: 250px;
            }
        }
    </style>
@endsection

@section('content')
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
                <option value="{{ route('destinasi.kategori', 'Culinary') }}" {{ $kategori === 'Culinary' ? 'selected' : '' }}>Culinary</option>
                <option value="{{ route('destinasi.kategori', 'Tourism') }}" {{ $kategori === 'Tourism' ? 'selected' : '' }}>Tourism</option>
                <option value="{{ route('destinasi.kategori', 'Shopping') }}" {{ $kategori === 'Shopping' ? 'selected' : '' }}>Shopping</option>
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
        @else
            <div class="empty-state">
                Tidak ada video TikTok tersedia untuk destinasi ini.
            </div>
        @endif

        <!-- Cards Grid -->
        @if($list->count() > 0)
            <div class="cards-grid">
                @foreach($list as $item)
                    <div class="card">
                        <div class="card-image">
                            @if($item['foto'])
                                <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $item['nama'] }}</h3>
                            <div class="card-rating">
                                <div class="rating-stars">
                                    @php
                                        $rating = $item['rating'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            $class = $i <= floor($rating) ? 'filled' : '';
                                            echo '<span class="star ' . $class . '">★</span>';
                                        }
                                    @endphp
                                </div>
                                <span class="rating-text">{{ number_format($rating, 1) }}</span>
                            </div>
                            <p class="card-description">{{ $item['deskripsi'] }}</p>
                            <div class="card-buttons">
                                <a href="{{ route('destinasi.show', $item['id']) }}" class="btn-primary">Lihat Detail</a>
                                @if(!empty($item['tiktok']))
                                    <a href="{{ $item['tiktok'] }}" target="_blank" class="btn-secondary">Tonton TikTok</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                Tidak ada destinasi ditemukan.
            </div>
        @endif

        <!-- Map Section -->
        <div class="map-section" style="margin-top: 40px;">
            <h3 class="section-title" style="text-align: center; margin-bottom: 20px;">Peta Lokasi Destinasi</h3>
            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
        </div>

    </div>

    <script>
        var map = L.map('map').setView([-6.92, 107.61], 13); // Museum Konferensi Asia Afrika center

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Special marker for Museum Konferensi Asia Afrika (center)
        var museumIcon = L.divIcon({
            className: 'museum-marker',
            html: '<div style="background-color: #dc2626; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">🏛️</div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        var museumMarker = L.marker([-6.92, 107.61], {icon: museumIcon}).addTo(map);
        museumMarker.bindPopup('<b>🏛️ Museum Konferensi Asia Afrika</b><br><i>Lokasi Pusat / Titik Tengah</i><br><small>Jl. Asia Afrika No.65, Bandung</small><br><button onclick="ambilRute(-6.92, 107.61)" style="margin-top: 8px; padding: 6px 12px; background-color: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Petunjuk Arah</button>');

        // Markers for other destinations
        var destinations = <?php echo json_encode($list); ?>;

        destinations.forEach(function(item) {
            var lat = item.koordinat && item.koordinat.lat ? item.koordinat.lat : null;
            var lng = item.koordinat && item.koordinat.lng ? item.koordinat.lng : null;
            if (lat && lng) {
                var marker = L.marker([lat, lng]).addTo(map);
                var alamatText = item.alamat || item.deskripsi || '';
                var popupContent = '<b>' + (item.nama || '') + '</b><br>Alamat: ' + alamatText +
                    '<br><button onclick="ambilRute(' + lat + ', ' + lng + ')" style="margin-top: 5px; padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">Ambil Rute</button>';
                marker.bindPopup(popupContent);
            }
        });

        function ambilRute(lat, lng) {
            var url = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
            window.open(url, '_blank');
        }
    </script>
@endsection
