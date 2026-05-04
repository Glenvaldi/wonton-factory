<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - WontonFactory</title>
    
    {{-- FONTS & ICONS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { 
            --bs-primary: #E31837; /* Wonton Red */
            --bs-dark: #1A1A1A;
            --bs-light: #F3F4F6;
            --sidebar-width: 260px;
        }
        
        body { 
            background-color: var(--bs-light); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden; 
        }

        /* --- SIDEBAR STYLING --- */
        .sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            z-index: 1040;
            background-color: white;
            border-right: 1px solid rgba(0,0,0,0.05);
            box-shadow: 4px 0 20px rgba(0,0,0,0.02);
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .brand-title {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            color: var(--bs-dark);
            margin-left: 10px;
        }

        .sidebar-nav {
            padding: 20px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-link {
            color: #64748b;
            padding: 14px 16px;
            margin-bottom: 4px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .nav-link i { font-size: 1.1rem; width: 30px; transition: transform 0.2s; }

        .nav-link:hover {
            background-color: #fff1f2;
            color: var(--bs-primary);
        }
        .nav-link:hover i { transform: translateX(3px); }

        .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(227, 24, 55, 0.3);
        }

        .sidebar-footer {
            padding: 20px;
            background-color: #f9fafb;
            border-top: 1px solid #eee;
        }

        /* --- CONTENT AREA --- */
        .main-content { 
            margin-left: var(--sidebar-width); 
            transition: margin-left 0.3s ease-in-out; 
            padding: 30px; 
            min-height: 100vh;
        }

        /* --- MOBILE RESPONSIVENESS --- */
        .mobile-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4); z-index: 1030;
            backdrop-filter: blur(2px);
            display: none;
            opacity: 0; transition: opacity 0.3s;
        }
        .mobile-overlay.show { display: block; opacity: 1; }

        .top-bar-mobile {
            background: white;
            padding: 12px 20px;
            display: none;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 1020;
        }

        @media (max-width: 992px) {
            .sidebar { left: -280px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; padding: 20px; }
            .top-bar-mobile { display: flex; }
        }
    </style>
</head>
<body>

    {{-- MOBILE TOP BAR --}}
    <div class="top-bar-mobile d-lg-none">
        <button class="btn btn-light border-0" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="fw-bold text-dark">Wonton Admin</span>
        <div style="width: 40px;"></div> {{-- Spacer --}}
    </div>

    {{-- SIDEBAR --}}
    <nav class="sidebar" id="sidebar">
        
        {{-- HEADER --}}
        <div class="sidebar-header">
            <img src="{{ asset('images/wonton-logo.png') }}" alt="Logo" width="36" height="36" class="object-fit-contain">
            <span class="brand-title">WONTON ADMIN</span>
            <button class="btn btn-sm btn-light ms-auto d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- MENU ITEMS --}}
        <div class="sidebar-nav">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block" style="font-size: 0.7rem; letter-spacing: 1px;">Main Menu</small>
            
            <a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a class="nav-link {{ Request::is('admin/orders') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-cart-check-fill"></i> Pesanan Masuk
                {{-- Badge Notifikasi (Contoh) --}}
                @php $newOrders = \App\Models\Order::where('status', 'pending')->count(); @endphp
                @if($newOrders > 0)
                    <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $newOrders }}</span>
                @endif
            </a>
            <a class="nav-link {{ Request::is('admin/menu') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                <i class="bi bi-egg-fried"></i> Kelola Menu
            </a>
            <a class="nav-link {{ Request::is('admin/banner') ? 'active' : '' }}" href="{{ route('admin.banner.index') }}">
                <i class="bi bi-images"></i> Banner & Promo
            </a>

            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block mt-4" style="font-size: 0.7rem; letter-spacing: 1px;">System</small>
            
            <a class="nav-link" href="{{ url('/') }}">  <i class="bi bi-box-arrow-up-right"></i> Lihat Website
            </a>
        </div>

        {{-- FOOTER --}}
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="d-flex flex-column overflow-hidden">
                    <span class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                    <span class="text-muted text-truncate" style="font-size: 0.75rem;">Super Admin</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm rounded-3 fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                </button>
            </form>
        </div>
    </nav>

    {{-- OVERLAY MOBILE --}}
    <div class="mobile-overlay" id="overlay"></div>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mobile Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('show');
        }

        toggleBtn.addEventListener('click', toggleMenu);
        closeBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    </script>
    @stack('scripts')
</body>
</html>