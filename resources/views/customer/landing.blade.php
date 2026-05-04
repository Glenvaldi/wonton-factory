@extends('layouts.app')

@section('content')

    {{-- 1. HERO SECTION BANNER SLIDER - FULL DISPLAY / NO CROP --}}
    <div class="relative w-full aspect-video overflow-hidden group bg-gray-900">
        @if(isset($banners) && count($banners) > 0)
            <div class="swiper mySwiper w-full h-full">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        @if($banner->is_active)
                        <div class="swiper-slide relative w-full h-full">
                            
                            {{-- GAMBAR BANNER UTUH DI SEMUA LAYAR --}}
                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                     class="w-full h-full object-contain" 
                                     alt="{{ $banner->title }}">
                            </div>
                            
                            {{-- OVERLAY TEXT (Opsional, menyesuaikan posisi agar tidak menutupi gambar utama) --}}
                            <div class="absolute inset-0 flex flex-col justify-end pb-6 px-6 md:pb-12 md:px-20 pointer-events-none">

                                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent"></div>

                                <div class="max-w-4xl mx-auto md:mx-0 text-center md:text-left relative z-10 animate-fade-in-up pointer-events-auto">
                                    <span class="inline-block py-1 px-3 rounded-full bg-brand-red/90 text-white text-[10px] md:text-xs font-bold tracking-widest mb-2 uppercase shadow-lg">
                                        🔥 Promo
                                    </span>
                                    
                                    {{-- Judul Besar --}}
                                    <h2 class="text-2xl md:text-5xl font-extrabold text-white mb-2 md:mb-4 leading-tight drop-shadow-md">
                                        {{ $banner->title }}
                                    </h2>

                                    {{-- Tombol Aksi --}}
                                    @if($banner->link_url)
                                        <div class="mt-2">
                                            <a href="{{ $banner->link_url }}" class="group inline-flex items-center gap-2 bg-white text-brand-red px-5 py-2 md:px-8 md:py-3 rounded-full font-bold text-xs md:text-sm hover:bg-brand-gray transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                                                <span>Lihat Detail</span>
                                                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="swiper-pagination !bottom-4"></div>
            </div>
        @else
            {{-- Default Hero Jika Tidak Ada Banner --}}
            <div class="w-full h-full flex items-center justify-center relative overflow-hidden bg-gray-900">
                <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/food.png')]"></div>
                <div class="text-center z-10 px-4">
                    <h1 class="text-3xl md:text-6xl font-extrabold text-white mb-2 tracking-tighter drop-shadow-2xl">
                        Wonton<span class="text-brand-red">Factory</span>
                    </h1>
                    <p class="text-gray-300 text-sm md:text-xl mb-6 font-light">
                        Nikmati kelezatan Asia yang sesungguhnya.
                    </p>
                    <a href="{{ route('menu.index') }}" class="bg-brand-red text-white px-8 py-3 rounded-full font-bold text-sm shadow-glow hover:bg-white hover:text-brand-red transition-all duration-300">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. LAYANAN KAMI --}}
    <div id="services-section" class="py-12 md:py-20 bg-gradient-to-b from-white to-gray-50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-8 md:mb-12 max-w-3xl mx-auto reveal-on-scroll">
                <h3 class="text-brand-red font-bold tracking-[0.2em] uppercase text-xs md:text-sm mb-2">Layanan Kami</h3>
                <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-2">Cara Menikmati Wonton</h2>
                <p class="text-gray-500 text-sm md:text-base">Pilih metode layanan yang paling nyaman untuk Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 max-w-6xl mx-auto">
                {{-- Delivery --}}
                <div data-service="delivery" class="service-card group relative cursor-pointer reveal-on-scroll delay-100">
                    <div class="relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-brand-red/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex flex-row md:flex-col items-center gap-4 text-left md:text-center">
                        <div class="w-14 h-14 bg-red-50 text-brand-red rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-brand-red group-hover:text-white transition-colors">
                            <i class="bi bi-truck-front-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Delivery</h3>
                            <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-0 md:mb-4">Kami antar pesanan hangat ke lokasi Anda.</p>
                            <span class="hidden md:inline-flex text-brand-red font-bold text-xs items-center gap-2 group-hover:gap-3 transition-all">Pilih <i class="bi bi-arrow-right"></i></span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-300 ml-auto md:hidden"></i>
                    </div>
                </div>

                {{-- Takeaway --}}
                <div data-service="takeaway" class="service-card group relative cursor-pointer reveal-on-scroll delay-200">
                    <div class="relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-orange-400/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex flex-row md:flex-col items-center gap-4 text-left md:text-center">
                        <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Takeaway</h3>
                            <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-0 md:mb-4">Pesan via web, ambil di outlet tanpa antri.</p>
                            <span class="hidden md:inline-flex text-orange-500 font-bold text-xs items-center gap-2 group-hover:gap-3 transition-all">Pilih <i class="bi bi-arrow-right"></i></span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-300 ml-auto md:hidden"></i>
                    </div>
                </div>

                {{-- Dine In --}}
                <div data-service="dinein" class="service-card group relative cursor-pointer reveal-on-scroll delay-300">
                    <div class="relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-blue-400/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex flex-row md:flex-col items-center gap-4 text-left md:text-center">
                        <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <i class="bi bi-shop-window"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Dine In</h3>
                            <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-0 md:mb-4">Makan di tempat dengan suasana nyaman.</p>
                            <span class="hidden md:inline-flex text-blue-500 font-bold text-xs items-center gap-2 group-hover:gap-3 transition-all">Pilih <i class="bi bi-arrow-right"></i></span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-300 ml-auto md:hidden"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. PROMO MENARIK --}}
    <div class="py-12 md:py-20 bg-white relative">
        <div class="container mx-auto px-4">
            <div class="flex flex-row justify-between items-end mb-6 md:mb-10 reveal-on-scroll">
                <div>
                    <h3 class="text-brand-red font-bold tracking-widest uppercase text-xs md:text-sm mb-1">Penawaran Spesial</h3>
                    <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900">Promo Hari Ini</h2>
                </div>
                @if(isset($promos) && count($promos) > 0)
                    <a href="{{ route('menu.index') }}" class="flex items-center gap-1 text-brand-red font-bold text-xs md:text-sm hover:underline">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 reveal-on-scroll delay-200">
                @if(isset($promos) && count($promos) > 0)
                    @foreach($promos as $promo)
                    <div class="group relative h-48 md:h-56 rounded-2xl md:rounded-3xl overflow-hidden shadow-md cursor-pointer transform hover:scale-[1.02] transition-all duration-500">
                        <a href="{{ route('menu.index') }}">
                            <img src="{{ asset('storage/' . $promo->image) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Promo">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80 group-hover:opacity-90 transition duration-500"></div>
                            <div class="absolute top-3 left-3 md:top-4 md:left-4">
                                <span class="bg-brand-red text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-md uppercase tracking-wide animate-pulse">Hot Deal</span>
                            </div>
                            <div class="absolute bottom-0 left-0 p-4 md:p-6 w-full transform translate-y-2 group-hover:translate-y-0 transition duration-500">
                                <h3 class="text-white font-bold text-base md:text-lg mb-1">Pesan Sekarang</h3>
                                <div class="h-0.5 w-8 bg-brand-red group-hover:w-16 transition-all duration-500"></div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center">
                        <i class="bi bi-tag text-gray-300 text-3xl mb-2"></i>
                        <h4 class="text-sm font-bold text-gray-400">Belum ada promo aktif.</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 4. KEUNGGULAN KAMI --}}
    <div class="py-12 md:py-24 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-20 items-center">
                
                <div class="order-2 lg:order-1 reveal-on-scroll slide-right">
                    <h3 class="text-brand-red font-bold tracking-[0.25em] uppercase text-xs md:text-sm mb-2 flex items-center gap-2">
                        <span class="w-6 h-0.5 bg-brand-red inline-block"></span> WHY CHOOSE US
                    </h3>
                    <h2 class="text-2xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                        Kualitas Rasa yang <br class="hidden md:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500">Tak Tertandingi</span>
                    </h2>
                    
                    <div class="space-y-3 md:space-y-5">
                        <div class="flex items-start md:items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg bg-red-50 text-brand-red flex items-center justify-center text-xl md:text-2xl shrink-0">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-bold text-gray-900">100% Halal</h4>
                                <p class="text-xs md:text-sm text-gray-500">Bahan baku pilihan bersertifikat & higienis.</p>
                            </div>
                        </div>

                        <div class="flex items-start md:items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center text-xl md:text-2xl shrink-0">
                                <i class="bi bi-fire"></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-bold text-gray-900">Selalu Hangat</h4>
                                <p class="text-xs md:text-sm text-gray-500">Dimasak dadakan saat dipesan (Made to Order).</p>
                            </div>
                        </div>

                        <div class="flex items-start md:items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xl md:text-2xl shrink-0">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-bold text-gray-900">Harga Bersahabat</h4>
                                <p class="text-xs md:text-sm text-gray-500">Rasa bintang lima dengan harga kaki lima.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 relative reveal-on-scroll slide-left">
                    <div class="relative rounded-[1.5rem] md:rounded-[3rem] overflow-hidden shadow-xl border-4 md:border-8 border-white">
                        <img src="{{ asset('images/kualitas-produk.jpg') }}" 
                             alt="Kualitas Produk" 
                             class="w-full h-48 md:h-[500px] object-cover">
                        
                        <div class="absolute bottom-4 left-4 md:bottom-10 md:left-10 bg-white/95 backdrop-blur-md p-3 md:p-5 rounded-xl md:rounded-2xl shadow-lg flex items-center gap-3 md:gap-4 max-w-[160px] md:max-w-xs animate-bounce-slow">
                            <div class="w-8 h-8 md:w-12 md:h-12 bg-brand-red rounded-full flex items-center justify-center text-white font-bold text-xs md:text-base">
                                10+
                            </div>
                            <div>
                                <p class="text-[8px] md:text-xs text-gray-500 uppercase font-bold tracking-wider">Pengalaman</p>
                                <p class="text-xs md:text-base font-extrabold text-gray-900">Sejak 2015</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 5. CTA --}}
    <div class="py-16 md:py-20 relative bg-brand-dark overflow-hidden flex items-center justify-center text-center">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="container mx-auto px-4 relative z-10 reveal-on-scroll zoom-in">
            <h2 class="text-2xl md:text-5xl font-extrabold text-white mb-3 md:mb-4 tracking-tight">Lapar? Pesan Sekarang!</h2>
            <p class="text-gray-400 text-xs md:text-lg mb-6 md:mb-8 max-w-xl mx-auto font-light">
                Rasakan kenikmatan Wonton terbaik di kota ini. Cepat, Mudah, & Lezat.
            </p>
            <a href="{{ route('menu.index') }}" class="bg-brand-red text-white px-8 py-3 md:px-10 md:py-4 rounded-full font-bold text-sm md:text-lg shadow-glow hover:bg-white hover:text-brand-red hover:scale-105 transition-all duration-300">
                Lihat Menu
            </a>
        </div>
    </div>

    {{-- STYLE & SCRIPT --}}
    <style>
        @keyframes zoomIn { from { transform: scale(1); } to { transform: scale(1.1); } }
        @keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
        .reveal-on-scroll { opacity: 0; transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); transform: translateY(20px); }
        .reveal-on-scroll.visible { opacity: 1; transform: translateY(0); }
        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.removeItem('selected_service');

            if(document.querySelector('.mySwiper')) {
                var swiper = new Swiper(".mySwiper", {
                    loop: true,
                    effect: "fade",
                    autoplay: { delay: 4000, disableOnInteraction: false },
                    pagination: { el: ".swiper-pagination", clickable: true },
                });
            }

            const cards = document.querySelectorAll('.service-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const service = this.getAttribute('data-service');
                    localStorage.setItem('selected_service', service);
                    this.style.transform = "scale(0.98)";
                    setTimeout(() => { window.location.href = "{{ route('menu.index') }}"; }, 150);
                });
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
@endsection