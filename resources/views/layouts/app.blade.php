<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', 'KAA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .navbar { background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.06); position: sticky; top: 0; z-index: 50; }
        .navbar-content { max-width: 1280px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 70px; }
        .logo-section { display: flex; align-items: center; gap: 12px; font-weight: 700; color: #dc2626; font-size: 18px; }
        .nav-menu { display: flex; align-items: center; gap: 18px; }
        .nav-link { color: #334155; font-size: 14px; text-decoration: none; }
        .nav-cta { background: #dc2626; color: white; padding: 8px 12px; border-radius: 6px; font-weight: 600; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo-section">
                🏛️ Museum KAA
            </div>

            <div style="display:flex;align-items:center;gap:20px;">
                <div class="nav-menu">
                    <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('destinasi.index') }}" class="nav-link">Destinasi Sekitar</a>
                </div>

                <div style="display:flex;align-items:center;gap:12px">
                    @auth
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="nav-cta" type="submit">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
