@extends('layouts.public')

@section('title', 'Video TikTok - ' . $data['nama'] . ' - Museum KAA')

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
            max-width: 1000px;
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
            max-width: 1000px;
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
        .content-wrapper {
            background: white;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 32px;
            align-items: start;
        }
        .video-container {
            background: #f3f4f6;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 500px;
        }
        .video-placeholder {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #9ca3af;
            gap: 8px;
        }
        .video-placeholder .title {
            font-weight: 600;
            font-size: 16px;
        }
        .video-placeholder .subtitle {
            font-size: 13px;
        }
        .video-footer {
            padding: 16px;
            border-top: 1px solid #e5e7eb;
            background: white;
        }
        .btn-tiktok {
            background: #dc2626;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        .btn-tiktok:hover {
            background: #b91c1c;
        }
        .comments-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            height: 500px;
            display: flex;
            flex-direction: column;
        }
        .comments-title {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
            margin-bottom: 16px;
        }
        .comments-list {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 16px;
        }
        .comment-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .comment-user {
            font-weight: 500;
            font-size: 13px;
            color: #111827;
            margin-bottom: 4px;
        }
        .comment-text {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }
        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .comment-form input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
        }
        .comment-form button {
            background: #dc2626;
            color: white;
            padding: 10px 12px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        .comment-form button:hover {
            background: #b91c1c;
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
            .video-container,
            .comments-section {
                height: 450px;
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
            .category-select {
                width: 100%;
                margin-bottom: 24px;
            }
            .content-wrapper {
                padding: 20px;
            }
            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .video-container,
            .comments-section {
                height: 350px;
            }
            .video-placeholder .title {
                font-size: 14px;
            }
            .video-placeholder .subtitle {
                font-size: 12px;
            }
            .video-footer {
                padding: 12px;
            }
            .btn-tiktok {
                width: 100%;
                text-align: center;
                padding: 12px 16px;
            }
            .comments-section {
                padding: 16px;
                height: 350px;
            }
            .comments-title {
                font-size: 14px;
                margin-bottom: 12px;
            }
            .comment-item {
                padding: 10px;
                margin-bottom: 8px;
            }
            .comment-user {
                font-size: 12px;
            }
            .comment-text {
                font-size: 12px;
            }
            .comment-form {
                gap: 6px;
            }
            .comment-form input {
                padding: 8px 10px;
                font-size: 12px;
            }
            .comment-form button {
                padding: 8px 10px;
                font-size: 12px;
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
                margin-bottom: 20px;
            }
            .content-wrapper {
                padding: 16px;
            }
            .content-grid {
                gap: 16px;
            }
            .video-container,
            .comments-section {
                height: 280px;
            }
            .video-placeholder {
                gap: 6px;
            }
            .video-placeholder .title {
                font-size: 13px;
            }
            .video-placeholder .subtitle {
                font-size: 11px;
            }
            .video-footer {
                padding: 10px;
            }
            .btn-tiktok {
                padding: 10px 14px;
                font-size: 13px;
            }
            .comments-section {
                padding: 12px;
                height: 280px;
            }
            .comments-title {
                font-size: 13px;
                margin-bottom: 10px;
            }
            .comment-item {
                padding: 8px;
                margin-bottom: 6px;
            }
            .comment-user {
                font-size: 11px;
                margin-bottom: 2px;
            }
            .comment-text {
                font-size: 11px;
            }
            .comment-form input {
                padding: 6px 8px;
                font-size: 11px;
            }
            .comment-form button {
                padding: 6px 8px;
                font-size: 11px;
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

        <div class="content-wrapper">
            <div class="content-grid">
                <!-- Video Section -->
                <div class="video-container">
                    @if($videoId)
                        <iframe
                            src="https://www.tiktok.com/embed/v2/{{ $videoId }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            style="width: 100%; height: 100%;"
                        ></iframe>
                    @else
                        <div class="video-placeholder">
                            <div class="title">{{ $data['nama'] }}</div>
                            <div class="subtitle">(Video TikTok)</div>
                        </div>
                        <div class="video-footer">
                            <a href="{{ $data['tiktok'] }}" target="_blank" class="btn-tiktok">Buka di TikTok</a>
                        </div>
                    @endif
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3 class="comments-title">Komentar ({{ count($comments) }} komentar)</h3>
                    <div class="comments-list">
                        @if(empty($comments))
                            <div class="comment-item">
                                <div class="comment-user">System</div>
                                <div class="comment-text">Memuat komentar...</div>
                            </div>
                        @else
                            @foreach($comments as $c)
                                <div class="comment-item">
                                    <div class="comment-user">{{ is_string($c['user'] ?? null) ? $c['user'] : '@user' . rand(100, 999) }}</div>
                                    <div class="comment-text">{{ is_string($c['text'] ?? null) ? $c['text'] : 'Komentar tidak tersedia' }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <form class="comment-form">
                        <input type="text" placeholder="Tulis komentar..." readonly>
                        <button type="button">Kirim</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection