<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WontonFactory - Taste of Authenticity' }}</title>

    {{-- ================================================================= --}}
    {{-- 1. FAVICON (Ikon Kecil di Tab Browser) --}}
    {{-- ================================================================= --}}
    <link rel="icon" href="{{ asset('images/wonton-logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/wonton-logo.png') }}" type="image/png">

    {{-- ================================================================= --}}
    {{-- 2. GAMBAR LINK PREVIEW (Saat Share ke WA/IG/FB) --}}
    {{-- ================================================================= --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Wonton Factory - Asian Culinary">
    <meta property="og:description" content="Nikmati kelezatan Wonton autentik, Dimsum, dan hidangan Asia terbaik. Halal, Enak, & Terjangkau!">
    <meta property="og:image" content="{{ asset('images/wonton-logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- TAILWIND CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FONTS & ICONS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'brand-red': '#E31837',
                'brand-dark': '#1A1A1A',
                'brand-gray': '#F3F4F6',
              },
              fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
              boxShadow: {
                  'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                  'glow': '0 0 20px rgba(227, 24, 55, 0.3)',
              },
              animation: {
                  'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                  'pulse-slow': 'pulse 3s infinite',
              },
              keyframes: {
                  fadeInUp: {
                      '0%': { opacity: '0', transform: 'translateY(20px)' },
                      '100%': { opacity: '1', transform: 'translateY(0)' },
                  }
              }
            }
          }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFA; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fff; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #E31837; }
        .nav-scrolled {
            background: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        header { transition: all 0.4s ease-in-out; padding-top: 20px; padding-bottom: 20px; }
        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #fff; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.5s ease-out, visibility 0.5s;
        }
        #preloader.loaded { opacity: 0; visibility: hidden; }
        .icon-pop {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.3s ease;
            display: inline-block;
        }
        .group:hover .icon-pop { transform: scale(1.3) translateY(-2px); color: #E31837; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-gray-800">

    {{-- 1. PRELOADER --}}
    <div id="preloader">
        <div class="flex flex-col items-center animate-pulse-slow">
            <img src="{{ asset('images/wonton-logo.png') }}" alt="Loading..." class="w-16 h-16 md:w-20 md:h-20 mb-4 object-contain animate-bounce">
            <p class="text-brand-red font-bold tracking-[0.3em] text-xs md:text-sm uppercase">Wonton Factory</p>
        </div>
    </div>

    {{-- 2. NAVBAR (SOLID WHITE) --}}
    <header class="fixed w-full top-0 z-50 transition-all duration-300 bg-white border-b border-gray-100 shadow-sm" id="main-header">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex items-center justify-between">
                
                {{-- LOGO BRAND --}}
                <a href="/" class="flex items-center gap-2 md:gap-3 group shrink-0">
                    <div class="relative overflow-hidden rounded-full p-1 group-hover:bg-red-50 transition duration-500">
                        <img src="{{ asset('images/wonton-logo.png') }}" alt="Logo" class="h-9 w-9 md:h-12 md:w-12 object-contain transform group-hover:rotate-12 transition duration-500 drop-shadow-sm icon-pop">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg md:text-2xl font-extrabold tracking-tighter text-gray-900 leading-none group-hover:text-brand-red transition-colors duration-300">Wonton<span class="text-brand-red">Factory</span></span>
                        <span class="text-[9px] md:text-[11px] font-bold text-gray-400 tracking-[0.15em] uppercase group-hover:text-gray-600 transition">Asian Culinary</span>
                    </div>
                </a>
                
                {{-- DESKTOP MENU --}}
                <nav class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-sm font-bold text-gray-600">
                    <a href="/" class="group flex items-center gap-2 py-1 hover:text-brand-red transition-colors {{ Request::is('/') ? 'text-brand-red active' : '' }}">
                        <i class="bi bi-house-door-fill text-lg mb-0.5 icon-pop"></i> <span>BERANDA</span>
                    </a>
                    <a href="{{ route('menu.index') }}" class="group flex items-center gap-2 py-1 hover:text-brand-red transition-colors {{ Request::is('menu*') ? 'text-brand-red active' : '' }}">
                        <i class="bi bi-grid-fill text-lg mb-0.5 icon-pop"></i> <span>MENU</span>
                    </a>
                    <a href="{{ url('/track') }}" class="group flex items-center gap-2 py-1 hover:text-brand-red transition-colors {{ Request::is('track*') ? 'text-brand-red active' : '' }}">
                        <i class="bi bi-truck text-lg mb-0.5 icon-pop"></i> <span>LACAK</span>
                    </a>
                    <a href="{{ url('/contact') }}" class="group flex items-center gap-2 py-1 hover:text-brand-red transition-colors {{ Request::is('contact*') ? 'text-brand-red active' : '' }}">
                        <i class="bi bi-telephone-fill text-lg mb-0.5 icon-pop"></i> <span>KONTAK</span>
                    </a>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="group bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-brand-red hover:shadow-glow transition-all duration-300 transform hover:-translate-y-0.5 text-xs font-bold tracking-wider flex items-center gap-2">
                                <i class="bi bi-speedometer2 text-base icon-pop group-hover:text-white"></i> DASHBOARD
                            </a>
                        @endif
                    @endauth
                </nav>

                {{-- RIGHT ACTIONS --}}
                <div class="flex items-center gap-2 md:gap-4">
                    
                    {{-- AUTH BUTTONS (Desktop) --}}
                    <div class="hidden md:flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                {{-- Profile Dropdown --}}
                                <div class="relative group z-50 py-2">
                                    <button class="flex items-center gap-2 focus:outline-none p-1 pr-3 rounded-full hover:bg-gray-100 transition border border-transparent hover:border-gray-200">
                                        <div class="relative transition-transform duration-300 group-hover:scale-110">
                                            @if(Auth::user()->profile_photo_path)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-9 w-9 rounded-full object-cover border-2 border-white shadow-sm">
                                            @else
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-brand-red to-orange-500 text-white flex items-center justify-center shadow-md text-sm">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-left hidden xl:block">
                                            <p class="text-xs font-bold text-gray-800 leading-none max-w-[100px] truncate">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                            <p class="text-[9px] text-brand-red font-bold uppercase">Member</p>
                                        </div>
                                        <i class="bi bi-chevron-down text-gray-400 text-[10px] ml-1 group-hover:rotate-180 transition-transform duration-300"></i>
                                    </button>
                                    
                                    {{-- Dropdown Content --}}
                                    <div class="absolute right-0 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden group-hover:block animate-fade-in-up origin-top-right">
                                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="p-2 space-y-1 bg-white">
                                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-red-50 hover:text-brand-red rounded-xl transition font-medium group/item">
                                                <i class="bi bi-person-gear text-lg transition-transform group-hover/item:scale-125 group-hover/item:text-brand-red"></i> Profil Saya
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition font-medium mt-1 group/item">
                                                    <i class="bi bi-box-arrow-right text-lg transition-transform group-hover/item:scale-125 group-hover/item:text-red-600"></i> Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-brand-red font-bold text-sm px-4 py-2 transition hover:scale-105 transform">Masuk</a>
                                    <a href="{{ route('register') }}" class="bg-brand-dark text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-brand-red hover:shadow-glow transition-all duration-300 transform hover:-translate-y-0.5 hover:scale-105">Daftar</a>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- CART BUTTON --}}
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    <a href="{{ $cartCount > 0 ? (Route::has('checkout.index') ? route('checkout.index') : '/checkout') : 'javascript:void(0)' }}" 
                       onclick="{{ $cartCount == 0 ? "showEmptyCartAlert(event)" : "" }}"
                       class="relative group bg-brand-red text-white h-10 w-10 md:h-11 md:w-auto md:px-6 rounded-full flex items-center justify-center md:justify-between gap-2 transition-all duration-300 shadow-lg hover:shadow-red-200 hover:-translate-y-1 active:scale-95">
                        <i class="bi bi-bag-heart-fill text-lg md:text-lg group-hover:animate-bounce"></i>
                        <span class="hidden md:inline font-bold text-sm">Keranjang</span>
                        
                        @if($cartCount > 0)
                        <span id="navbar-cart-count" class="absolute -top-1 -right-1 md:static md:ml-1 bg-white text-brand-red md:px-2 md:py-0.5 h-4 w-4 md:h-5 md:w-5 md:h-auto md:w-auto flex items-center justify-center text-[10px] md:text-xs font-extrabold rounded-full shadow-sm animate-pulse border border-red-100">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>

                    {{-- MOBILE HAMBURGER --}}
                    <button id="mobile-menu-btn" class="lg:hidden text-gray-800 focus:outline-none p-2 active:scale-90 transition-transform rounded-lg hover:bg-gray-100 ml-1">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div id="mobile-menu" class="lg:hidden bg-white border-b border-gray-200 absolute w-full shadow-2xl hidden transition-all duration-300 left-0 z-40">
            <div class="px-5 pt-4 pb-8 space-y-2 bg-white">
                
                {{-- LINKS --}}
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <a href="/" class="flex flex-col items-center justify-center gap-2 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition border border-gray-100 hover:border-red-100 group">
                        <i class="bi bi-house-door-fill text-2xl text-gray-500 group-hover:text-brand-red transition"></i>
                        <span class="text-xs font-bold text-gray-700 group-hover:text-brand-red">Beranda</span>
                    </a>
                    <a href="{{ route('menu.index') }}" class="flex flex-col items-center justify-center gap-2 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition border border-gray-100 hover:border-red-100 group">
                        <i class="bi bi-grid-fill text-2xl text-gray-500 group-hover:text-brand-red transition"></i>
                        <span class="text-xs font-bold text-gray-700 group-hover:text-brand-red">Menu</span>
                    </a>
                    <a href="{{ url('/track') }}" class="flex flex-col items-center justify-center gap-2 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition border border-gray-100 hover:border-red-100 group">
                        <i class="bi bi-truck text-2xl text-gray-500 group-hover:text-brand-red transition"></i>
                        <span class="text-xs font-bold text-gray-700 group-hover:text-brand-red">Lacak</span>
                    </a>
                    <a href="{{ url('/contact') }}" class="flex flex-col items-center justify-center gap-2 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition border border-gray-100 hover:border-red-100 group">
                        <i class="bi bi-telephone-fill text-2xl text-gray-500 group-hover:text-brand-red transition"></i>
                        <span class="text-xs font-bold text-gray-700 group-hover:text-brand-red">Kontak</span>
                    </a>
                </div>
                
                @auth
                    {{-- TAMPILAN ADMIN DI HP (DASHBOARD BUTTON) --}}
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-3 mb-4 bg-gray-900 text-white rounded-xl font-bold text-sm shadow-md hover:bg-brand-red transition">
                            <i class="bi bi-speedometer2 text-lg"></i> DASHBOARD ADMIN
                        </a>
                    @endif

                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-200 mb-3">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="h-10 w-10 rounded-full bg-brand-red text-white flex items-center justify-center font-bold shadow-sm shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="overflow-hidden">
                                <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('customer.profile') }}" class="flex-1 text-center py-2.5 border border-gray-200 rounded-xl font-bold text-gray-700 text-sm hover:bg-gray-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-center py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pt-2">
                        <p class="text-center text-xs text-gray-400 mb-3 font-medium uppercase tracking-wider">Akses Member</p>
                        <div class="flex gap-3">
                            <a href="{{ route('login') }}" class="flex-1 text-center py-3 border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50">Masuk</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-3 bg-brand-red text-white rounded-xl font-bold shadow-lg hover:bg-red-700">Daftar</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    {{-- 3. SPACER --}}
    <div class="h-20 md:h-24"></div>

    {{-- 4. KONTEN UTAMA --}}
    <main class="flex-grow relative z-10 animate-fade-in-up">
        @yield('content')
    </main>

    {{-- 5. FOOTER --}}
    <footer class="bg-gray-900 text-white pt-12 pb-8 mt-auto">
        <div class="container mx-auto px-6 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
                
                {{-- Brand --}}
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
                        <img src="{{ asset('images/wonton-logo.png') }}" alt="Logo" class="h-8 w-8 grayscale brightness-200">
                        <span class="text-2xl font-extrabold tracking-tight">Wonton<span class="text-brand-red">Factory</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Menghadirkan cita rasa autentik Asian Culinary dengan sentuhan modern.
                    </p>
                    <div class="flex justify-center md:justify-start gap-4">
                        <a href="https://www.instagram.com/wontonfactory1" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-brand-red hover:text-white transition transform hover:-translate-y-1"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.tiktok.com/@wontonfactoryy" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-brand-red hover:text-white transition transform hover:-translate-y-1"><i class="bi bi-tiktok"></i></a>
                        <a href="https://wa.me/6281216145841" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-brand-red hover:text-white transition transform hover:-translate-y-1"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                {{-- Links --}}
                <div class="text-center md:text-left">
                    <h4 class="text-base font-bold mb-4 text-white uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/" class="hover:text-brand-red transition">Beranda</a></li>
                        <li><a href="{{ route('menu.index') }}" class="hover:text-brand-red transition">Menu Makanan</a></li>
                        <li><a href="{{ url('/track') }}" class="hover:text-brand-red transition">Lacak Pesanan</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="text-center md:text-left">
                    <h4 class="text-base font-bold mb-4 text-white uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex flex-col md:flex-row items-center md:items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-brand-red"></i>
                            <span>Jl. Nanas No 8, Ngantang, Malang</span>
                        </li>
                        <li class="flex flex-col md:flex-row items-center md:items-start gap-2">
                            <i class="bi bi-whatsapp text-brand-red"></i>
                            <a href="https://wa.me/6281216145841" target="_blank" class="hover:text-white transition">+62 812-1614-5841</a>
                        </li>
                        <li class="flex flex-col md:flex-row items-center md:items-start gap-2">
                            <i class="bi bi-envelope-fill text-brand-red"></i>
                            <span>wontonfactory@gmail.com</span>
                        </li>
                    </ul>
                </div>

                {{-- Hours --}}
                <div class="text-center md:text-left">
                    <h4 class="text-base font-bold mb-4 text-white uppercase tracking-wider">Jam Buka</h4>
                    <div class="bg-gray-800 p-4 rounded-xl inline-block w-full border border-gray-700">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-gray-400">Senin - Jumat</span>
                            <span class="font-bold text-white">10:00 - 22:00</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Sabtu - Minggu</span>
                            <span class="font-bold text-brand-red">09:00 - 23:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Wonton Factory. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white">Privacy</a>
                    <a href="#" class="hover:text-white">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- BACK TO TOP BUTTON --}}
    <button id="back-to-top" class="fixed bottom-24 right-4 bg-gray-900 text-white w-10 h-10 rounded-full shadow-glow hover:bg-brand-red transition-all duration-500 transform translate-y-20 opacity-0 z-40 flex items-center justify-center border-2 border-white/10" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        <i class="bi bi-arrow-up text-lg animate-bounce"></i>
    </button>

    {{-- MINI CART WIDGET --}}
    @if(!Request::is('checkout*'))
        @include('partials.mini_cart')
    @endif

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>
    
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('loaded');
                setTimeout(() => { preloader.style.display = 'none'; }, 500);
            }, 800);
        });

        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('nav-scrolled');
            } else {
                header.classList.remove('nav-scrolled');
            }
        });

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        function showEmptyCartAlert(event) {
            event.preventDefault(); 
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Ups! Anda belum memilih menu apapun.',
                confirmButtonText: 'Lihat Menu',
                confirmButtonColor: '#E31837',
                background: '#fff',
                color: '#333'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('menu.index') }}";
                }
            });
        }

        const cart = document.getElementById('floating-cart-bar');
        const footer = document.querySelector('footer');
        const backToTopBtn = document.getElementById('back-to-top');

        function handleScroll() {
            const viewportBottom = window.scrollY + window.innerHeight;
            const footerTop = footer.offsetTop;

            if (cart && cart.style.display !== 'none') {
                if (viewportBottom > footerTop) {
                    const overlap = viewportBottom - footerTop;
                    cart.style.bottom = `${overlap + 20}px`;
                } else {
                    cart.style.bottom = '0';
                }
            }

            if (window.scrollY > 400) {
                backToTopBtn.classList.remove('translate-y-20', 'opacity-0');
            } else {
                backToTopBtn.classList.add('translate-y-20', 'opacity-0');
            }
        }

        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', handleScroll);
        setTimeout(handleScroll, 50);
    </script>

    @stack('scripts')
</body>
</html>