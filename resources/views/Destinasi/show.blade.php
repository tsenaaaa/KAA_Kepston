@extends('layouts.public')

@section('title', $data['nama'] . ' - Museum KAA')

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
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 16px;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: #111827;
        }
        .main-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        .category-select {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            margin-bottom: 32px;
            width: 200px;
        }
        .title-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #111827;
        }
        .content-wrapper {
            background: white;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: start;
        }
        .image-container {
            background: #f3f4f6;
            border-radius: 8px;
            overflow: hidden;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .description-section {
            display: flex;
            flex-direction: column;
        }
        .description-text {
            color: #374151;
            line-height: 1.8;
            font-size: 15px;
            margin-bottom: 32px;
        }
        .btn-tiktok {
            background: #dc2626;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
            width: fit-content;
        }
        .btn-tiktok:hover {
            background: #b91c1c;
        }
        .rating-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .rating-stars {
            display: flex;
            gap: 2px;
        }
        .star {
            color: #ddd;
            font-size: 16px;
        }
        .star.filled {
            color: #fbbf24;
        }
        .rating-text {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }
        .reviews-count {
            font-size: 12px;
            color: #6b7280;
        }
        .reviews-section {
            margin-bottom: 24px;
        }
        .reviews-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }
        .review-item {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .review-author {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        .review-text {
            color: #4b5563;
            font-style: italic;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .review-rating {
            display: flex;
            gap: 2px;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .btn-maps {
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        .btn-maps:hover {
            background: #1d4ed8;
        }
        .photo-gallery {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 32px;
        }
        .gallery-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        .gallery-item {
            background: #f3f4f6;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .gallery-item img:hover {
            transform: scale(1.05);
        }
        @media (max-width: 1024px) {
            .main-content {
                padding: 32px 20px;
            }
            .breadcrumb-content {
                padding: 0 20px;
            }
            .content-wrapper {
                padding: 24px;
            }
            .content-grid {
                gap: 24px;
            }
        }

        @media (max-width: 768px) {
            .breadcrumb-section {
                padding: 20px 0;
            }
            .breadcrumb-content {
                padding: 0 20px;
            }
            .main-content {
                padding: 24px 16px;
            }
            .page-title {
                font-size: 24px;
            }
            .content-wrapper {
                padding: 20px;
            }
            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .image-container {
                min-height: 250px;
            }
            .description-text {
                font-size: 14px;
                margin-bottom: 24px;
            }
            .rating-section {
                padding: 12px;
                margin-bottom: 20px;
            }
            .rating-stars .star {
                font-size: 14px;
            }
            .rating-text {
                font-size: 13px;
            }
            .reviews-count {
                font-size: 11px;
            }
            .tiktok-comments-card {
                max-height: 250px !important;
                padding: 12px !important;
            }
            .tiktok-comments-card h4 {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }
            .comment-item {
                padding: 8px !important;
            }
            .comment-user {
                font-size: 13px !important;
            }
            .comment-text {
                font-size: 13px !important;
            }
            .btn-tiktok {
                padding: 10px 20px;
                font-size: 13px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            .photo-gallery {
                margin-top: 32px;
                padding-top: 24px;
            }
            .gallery-title {
                font-size: 18px;
                margin-bottom: 16px;
            }
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-section {
                padding: 16px 0;
            }
            .breadcrumb-content {
                padding: 0 16px;
            }
            .back-link {
                font-size: 13px;
                margin-bottom: 12px;
            }
            .main-content {
                padding: 20px 12px;
            }
            .category-select {
                width: 100%;
                margin-bottom: 24px;
            }
            .title-section {
                margin-bottom: 24px;
            }
            .page-title {
                font-size: 20px;
            }
            .content-wrapper {
                padding: 16px;
            }
            .content-grid {
                gap: 16px;
            }
            .image-container {
                min-height: 200px;
            }
            .description-text {
                font-size: 13px;
                margin-bottom: 20px;
                line-height: 1.6;
            }
            .rating-section {
                padding: 10px;
                margin-bottom: 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .rating-stars .star {
                font-size: 13px;
            }
            .rating-text {
                font-size: 12px;
            }
            .reviews-count {
                font-size: 10px;
            }
            .tiktok-comments-card {
                max-height: 200px !important;
                padding: 10px !important;
                margin-bottom: 20px !important;
            }
            .tiktok-comments-card h4 {
                font-size: 13px !important;
                margin-bottom: 6px !important;
            }
            .comment-item {
                padding: 6px !important;
            }
            .comment-user {
                font-size: 12px !important;
                margin-bottom: 2px !important;
            }
            .comment-text {
                font-size: 12px !important;
            }
            .btn-tiktok {
                width: 100%;
                text-align: center;
                padding: 12px 16px;
                font-size: 13px;
            }
            .photo-gallery {
                margin-top: 24px;
                padding-top: 20px;
            }
            .gallery-title {
                font-size: 16px;
                margin-bottom: 12px;
            }
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 8px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="breadcrumb-content">
            <a href="{{ route('destinasi.index') }}" class="back-link">← Kembali ke Daftar</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <select class="category-select">
            <option>Culinary</option>
            <option>Tourism</option>
            <option>Shopping</option>
        </select>

        <div class="title-section">
            <h1 class="page-title">{{ $data['nama'] }}</h1>
        </div>

        <div class="content-wrapper">
            <div class="content-grid">
                <!-- Image -->
                <div class="image-container">
                    @if(!empty($data['foto']))
                        <img src="{{ $data['foto'] }}" alt="{{ $data['nama'] }}">
                    @else
                        <span>No Image</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="description-section">
                    <p class="description-text">{{ $data['deskripsi'] }}</p>

                    @if($data['rating'])
                        <div class="rating-section">
                            <div class="rating-stars">
                                @php
                                    $rating = $data['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        $class = $i <= floor($rating) ? 'filled' : '';
                                        echo '<span class="star ' . $class . '">★</span>';
                                    }
                                @endphp
                            </div>
                            <span class="rating-text">{{ number_format($rating, 1) }}</span>
                            @if($data['reviews_count'])
                                <span class="reviews-count">({{ number_format($data['reviews_count']) }} reviews)</span>
                            @endif
                        </div>
                    @endif

                    <!-- TikTok Comments Card -->
                    @if(!empty($comments))
                        <div class="tiktok-comments-card" style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-bottom: 24px; max-height: 300px; overflow-y: auto;">
                            <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px;">Komentar TikTok ({{ count($comments) }})</h4>
                            <div class="comments-list" style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach($comments as $comment)
                                    <div class="comment-item" style="background: white; padding: 12px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        <div class="comment-user" style="font-weight: 600; font-size: 14px; color: #111827; margin-bottom: 4px;">{{ is_string($comment['user'] ?? null) ? $comment['user'] : '@user' . rand(100, 999) }}</div>
                                        <div class="comment-text" style="font-size: 14px; color: #4b5563;">{{ is_string($comment['text'] ?? null) ? $comment['text'] : 'Komentar tidak tersedia' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @if(!empty($data['tiktok']))
                            <a href="{{ $data['tiktok'] }}" target="_blank" class="btn-tiktok">Lihat di TikTok</a>
                        @endif

                        @if(!empty($data['koordinat']['lat']) && !empty($data['koordinat']['lng']))
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($data['nama']) }}" target="_blank" class="btn-tiktok" style="background: #4285f4;">Lihat di Google Maps</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(isset($data['photos']) && count($data['photos']) > 1)
            <div class="photo-gallery">
                <h3 class="gallery-title">Galeri Foto</h3>
                <div class="gallery-grid">
                    @foreach(array_slice($data['photos'], 1, 8) as $photo)
                        <div class="gallery-item">
                            <img src="{{ $photo['url'] }}" alt="{{ $data['nama'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
