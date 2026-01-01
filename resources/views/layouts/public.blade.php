<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Museum KAA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('head')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f5f5;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        /* Desktop Navbar */
        .navbar-desktop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 0 40px;
            height: 70px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 50;
            min-width: 0;
            overflow: hidden;
        }

        .navbar-desktop .hamburger-menu {
            display: none !important;
        }

        /* Mobile Navbar */
        .navbar-mobile {
            display: none;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 0 20px;
            height: 60px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-mobile .hamburger-menu {
            order: -1; /* Ensure hamburger is on the left */
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .logo img {
            height: 50px;
            width: auto;
        }

        .logo-text {
            font-size: 13px;
            line-height: 1.2;
            color: #333;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 6px;
            flex-shrink: 1;
            min-width: 0;
        }

        .nav-menu li a {
            display: block;
            padding: 22px 16px;
            text-decoration: none;
            color: #333;
            font-size: 15px;
            white-space: nowrap;
        }

        .nav-menu li a:hover {
            background: #2563eb;
            color: #fff;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 4px;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f3f4f6;
        }

        .dropdown-content a:hover {
            background-color: #f8fafc;
            color: #2563eb;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .right-menu {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .flag {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        .admin-link {
            background: #dc2626;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .nav-cta {
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .user-info {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Hamburger Menu */
        .hamburger-menu {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 30px;
            height: 30px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .hamburger-menu span {
            width: 30px;
            height: 3px;
            background: #333;
            border-radius: 2px;
            transition: all 0.3s;
            transform-origin: center;
        }

        .hamburger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .mobile-sidebar.active {
            left: 0;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .close-sidebar {
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .close-sidebar:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .sidebar-menu {
            flex: 1;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #f3f4f6;
        }

        .sidebar-menu li:last-child {
            border-bottom: none;
        }

        .sidebar-menu li a {
            display: block;
            padding: 16px 20px;
            color: #374151;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .sidebar-menu li a:hover {
            background: #f9fafb;
            color: #2563eb;
        }

        .sidebar-footer {
            border-top: 1px solid #e5e7eb;
            padding: 20px;
            background: #f9fafb;
        }

        .sidebar-flags {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .sidebar-flags .flag {
            width: 32px;
            height: 32px;
        }

        .sidebar-admin-link {
            display: block;
            padding: 8px 12px;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            background: #dc2626;
            color: white;
            margin-bottom: 12px;
            transition: background 0.3s;
        }

        .sidebar-admin-link:hover {
            background: #b91c1c;
        }

        .sidebar-login-link {
            display: block;
            padding: 8px 12px;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: background 0.3s;
        }

        .sidebar-login-link {
            background: #f3f4f6;
            color: #374151;
        }

        .sidebar-login-link:hover {
            background: #e5e7eb;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .navbar-desktop .nav-menu {
                gap: 4px;
            }
            .navbar-desktop .nav-menu li a {
                padding: 22px 12px;
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            .navbar-desktop {
                display: none !important;
            }
            .navbar-mobile {
                display: flex !important;
            }
        }

        @media (max-width: 480px) {
            .navbar-mobile {
                padding: 0 12px;
                height: 56px;
            }
            .navbar-mobile .logo img {
                height: 40px;
            }
            .navbar-mobile .hamburger-menu {
                width: 24px;
                height: 24px;
            }
            .navbar-mobile .hamburger-menu span {
                width: 24px;
                height: 2px;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <!-- Desktop Navbar -->
    <nav class="navbar-desktop">
        <div class="logo">
            <img src="/logoKAA.png" alt="Museum KAA Logo" style="max-height: 50px;" />
        </div>

        <ul class="nav-menu">
            <li>
                <a href="{{ url('/') }}">Beranda</a>
            </li>
            <li>
                <a href="#">Museum KAA ▼</a>
            </li>
            <li><a href="#">Berita</a></li>
            <li>
                <a href="#">Reservasi ▼</a>
            </li>
            <li><a href="#">Struktur Organisasi</a></li>
            <li><a href="#">Virtual Museum</a></li>
            <li>
                <a href="{{ route('destinasi.index') }}">Destinasi Sekitar</a>
            </li>
        </ul>

        <div class="right-menu">
            <img class="flag" src="https://flagcdn.com/w40/id.png" alt="Indonesia" title="Bahasa Indonesia" />
            <img class="flag" src="https://flagcdn.com/w40/gb.png" alt="English" title="English" />

            @auth
                <a href="{{ route('admin.destinasi.index') }}" class="admin-link">Admin</a>
            @else
                <a href="{{ url('/login') }}" style="padding: 8px 16px; color: #333; text-decoration: none; font-size: 14px;">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Mobile Navbar -->
    <nav class="navbar-mobile">
        <button class="hamburger-menu" id="hamburger-menu" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="logo">
            <img src="/logoKAA.png" alt="Museum KAA Logo" style="max-height: 50px;" />
        </div>
    </nav>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="sidebar-header">
            <img src="/logoKAA.png" alt="Museum KAA Logo" style="max-height: 40px;" />
            <button class="close-sidebar" id="close-sidebar" aria-label="Close menu">×</button>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('/') }}">Beranda</a>
            </li>
            <li>
                <a href="#">Museum KAA ▼</a>
            </li>
            <li><a href="#">Berita</a></li>
            <li>
                <a href="#">Reservasi ▼</a>
            </li>
            <li><a href="#">Struktur Organisasi</a></li>
            <li><a href="#">Virtual Museum</a></li>
            <li>
                <a href="{{ route('destinasi.index') }}">Destinasi Sekitar</a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <!-- Admin button positioned higher up -->
            <a href="{{ route('admin.destinasi.index') }}" class="sidebar-admin-link">Admin</a>

            <div class="sidebar-flags">
                <img class="flag" src="https://flagcdn.com/w40/id.png" alt="Indonesia" title="Bahasa Indonesia" />
                <img class="flag" src="https://flagcdn.com/w40/gb.png" alt="English" title="English" />
            </div>

            @auth
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top: 8px;">
                        @csrf
                        <button class="sidebar-logout-btn" type="submit">Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ url('/login') }}" class="sidebar-login-link">Login</a>
            @endauth
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    @yield('content')

    @yield('scripts')

    <script>
        // Mobile Sidebar Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburger-menu');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Open sidebar
            hamburgerMenu.addEventListener('click', function() {
                mobileSidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                hamburgerMenu.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            });

            // Close sidebar functions
            function closeSidebarFunc() {
                mobileSidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                hamburgerMenu.classList.remove('active');
                document.body.style.overflow = ''; // Restore scroll
            }

            closeSidebar.addEventListener('click', closeSidebarFunc);
            sidebarOverlay.addEventListener('click', closeSidebarFunc);

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileSidebar.classList.contains('active')) {
                    closeSidebarFunc();
                }
            });

            // Close sidebar when clicking on menu items
            const sidebarLinks = mobileSidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Small delay to allow navigation
                    setTimeout(closeSidebarFunc, 100);
                });
            });
        });
    </script>
</body>
</html>