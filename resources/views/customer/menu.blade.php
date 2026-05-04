@extends('layouts.app')

@section('content')

{{-- 1. HEADER SECTION (DESAIN PREMIUM) --}}
<div class="relative bg-brand-dark text-white pt-32 pb-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-red rounded-full filter blur-[100px] opacity-30"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500 rounded-full filter blur-[80px] opacity-20"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="text-brand-red font-bold tracking-[0.2em] uppercase text-xs md:text-sm mb-2 block animate-fade-in-up">Order Now</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 tracking-tight animate-fade-in-up">
            Daftar <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500">Menu Spesial</span>
        </h1>
        <p class="text-gray-400 text-base md:text-lg max-w-2xl mx-auto animate-fade-in-up delay-100 mb-8">
            Pilih hidangan favorit Anda dari koleksi resep autentik kami.
        </p>

        {{-- Tombol Ganti Layanan --}}
        <div class="animate-fade-in-up delay-200 inline-block">
            <button id="btn-change-service" class="hidden inline-flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 px-6 py-2 rounded-full hover:bg-brand-red hover:border-brand-red transition-all duration-300 group cursor-pointer shadow-lg">
                <div class="flex flex-col text-right">
                    <span class="text-gray-300 text-[10px] uppercase tracking-widest font-bold">Mode Pesanan</span>
                    <span id="current-service-label" class="font-bold text-white text-sm leading-none">Loading...</span>
                </div>
                <div class="bg-white/20 p-1.5 rounded-full group-hover:bg-white group-hover:text-brand-red transition-colors">
                    <i class="bi bi-pencil-fill text-xs"></i>
                </div>
            </button>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 relative -mt-16 z-20 pb-20">

    {{-- 2. SEARCH & FILTER BAR --}}
    <div class="bg-white rounded-3xl shadow-xl p-5 md:p-6 mb-12 animate-fade-in-up delay-300 border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-brand-red"></div>
        
        <form action="{{ route('menu.index') }}" method="GET">
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                
                {{-- Kategori Filter --}}
                <div class="flex flex-wrap justify-center lg:justify-start gap-2 w-full lg:w-auto">
                    <button type="submit" name="category" value="all" 
                            class="px-5 py-2 rounded-full text-xs md:text-sm font-bold transition-all duration-300 border 
                            {{ request('category', 'all') == 'all' ? 'bg-brand-red text-white border-brand-red shadow-md transform scale-105' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-red-50 hover:border-brand-red hover:text-brand-red' }}">
                        Semua
                    </button>

                    @php $categories = ['Appetizer', 'Main Course', 'Dessert', 'Drink']; @endphp
                    @foreach($categories as $category)
                        <button type="submit" name="category" value="{{ $category }}" 
                                class="px-5 py-2 rounded-full text-xs md:text-sm font-bold transition-all duration-300 border 
                                {{ request('category') == $category ? 'bg-brand-red text-white border-brand-red shadow-md transform scale-105' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-red-50 hover:border-brand-red hover:text-brand-red' }}">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                {{-- Search Input --}}
                <div class="relative w-full lg:w-80 group">
                    <input type="text" name="search" placeholder="Cari menu favorit..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-red focus:bg-white transition-all shadow-inner text-sm group-hover:border-brand-red/50">
                    <i class="bi bi-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-brand-red transition-colors"></i>
                    @if(request('search'))
                        <a href="{{ route('menu.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-400 hover:text-red-600 transition" title="Hapus Pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- 3. GRID MENU --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($menus as $menu)
            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group flex flex-col h-full relative">
                
                {{-- Gambar Menu --}}
                <div class="h-60 overflow-hidden relative bg-gray-100">
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-in-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition duration-500"></div>
                    <span class="absolute top-4 left-4 bg-white/95 backdrop-blur text-gray-900 text-[10px] font-extrabold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                        {{ $menu->category ?? 'Menu' }}
                    </span>
                    
                    {{-- Overlay jika Stok Habis / Not Available --}}
                    @if(!$menu->is_available || $menu->stock <= 0)
                        <div class="absolute inset-0 bg-black/70 flex items-center justify-center backdrop-blur-sm z-10">
                            <span class="text-white font-extrabold border-2 border-white px-6 py-2 rounded-xl tracking-widest rotate-[-12deg] text-lg shadow-lg">HABIS</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-grow relative">
                    <div class="mb-1 mt-2">
                        <h3 class="text-lg font-extrabold text-gray-900 leading-tight group-hover:text-brand-red transition-colors line-clamp-2">{{ $menu->name }}</h3>
                    </div>

                    {{-- INFO STOK (DITAMBAHKAN DI SINI) --}}
                    <div class="mb-4">
                        @if($menu->stock > 0)
                            @if($menu->stock < 5)
                                {{-- Stok Menipis (Merah & Berkedip) --}}
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full animate-pulse">
                                    <i class="bi bi-fire"></i> Sisa {{ $menu->stock }} porsi lagi!
                                </span>
                            @else
                                {{-- Stok Aman --}}
                                <span class="inline-flex items-center gap-1 text-gray-400 text-[11px] font-medium">
                                    <i class="bi bi-box-seam"></i> Stok tersedia: {{ $menu->stock }}
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1 text-gray-400 text-[11px] font-medium">
                                <i class="bi bi-x-circle"></i> Stok Habis
                            </span>
                        @endif
                    </div>
                    
                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div><p class="text-xl font-black text-brand-red tracking-tight">Rp {{ number_format($menu->price, 0, ',', '.') }}</p></div>
                        
                        @if($menu->is_available && $menu->stock > 0)
                            <form action="{{ route('cart.add', $menu->id) }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <button type="submit" class="btn-add px-4 py-2 rounded-xl bg-gray-900 text-white text-xs font-bold shadow-md hover:bg-brand-red hover:shadow-lg transition-all duration-300 flex items-center gap-2 group/btn">
                                    <span>Pesan</span>
                                    <i class="bi bi-arrow-right transition-transform group-hover/btn:translate-x-1"></i>
                                </button>
                            </form>
                        @else
                            <button disabled class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 text-xs font-bold cursor-not-allowed">Habis</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <div class="inline-block p-8 rounded-full bg-red-50 mb-6 animate-bounce">
                    <i class="bi bi-search text-5xl text-brand-red/50"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Menu Tidak Ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba kata kunci lain atau ganti kategori.</p>
                @if(request('search') || request('category'))
                    <a href="{{ route('menu.index') }}" class="inline-block px-8 py-3 bg-brand-red text-white rounded-full font-bold hover:bg-red-700 transition shadow-lg">Reset Filter</a>
                @endif
            </div>
        @endforelse
    </div>
</div>

{{-- ====================================================================== --}}
{{-- MODAL PILIH LAYANAN (DIPERBAIKI UNTUK SCROLLING DI MOBILE & POSISI ATAS) --}}
{{-- ====================================================================== --}}
<div id="service-modal" class="fixed inset-0 z-[9999] bg-brand-dark/80 backdrop-blur-sm flex items-start justify-center pt-20 p-4 overflow-y-auto hidden transition-opacity duration-300 opacity-0 pointer-events-none">
    
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl overflow-hidden transform scale-90 transition-transform duration-300 max-h-[85vh] flex flex-col pointer-events-auto" id="modal-content">
        
        <div class="bg-brand-red p-6 md:p-8 text-center relative overflow-hidden shrink-0">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-white relative z-10">Selamat Datang!</h2>
            <p class="text-white/80 text-sm md:text-base mt-2 relative z-10">Mau makan enak lewat mana hari ini?</p>
        </div>

        <div class="p-6 md:p-10 overflow-y-auto overscroll-contain" style="-webkit-overflow-scrolling: touch;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                
                {{-- Delivery --}}
                <div onclick="selectService('delivery')" class="group cursor-pointer bg-white border-2 border-gray-100 hover:border-brand-red p-6 rounded-3xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-row md:flex-col items-center gap-5 md:gap-2 text-left md:text-center">
                    <div class="w-16 h-16 bg-red-50 text-brand-red rounded-2xl flex items-center justify-center shadow-sm group-hover:bg-brand-red group-hover:text-white transition-colors flex-shrink-0">
                        <i class="bi bi-truck-front-fill text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-brand-red md:mt-4 transition-colors">Delivery</h3>
                        <p class="text-xs text-gray-500 leading-snug">Kami antar hangat ke lokasi Anda.</p>
                    </div>
                </div>

                {{-- Takeaway --}}
                <div onclick="selectService('takeaway')" class="group cursor-pointer bg-white border-2 border-gray-100 hover:border-orange-500 p-6 rounded-3xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-row md:flex-col items-center gap-5 md:gap-2 text-left md:text-center">
                    <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center shadow-sm group-hover:bg-orange-500 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="bi bi-bag-check-fill text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-500 md:mt-4 transition-colors">Takeaway</h3>
                        <p class="text-xs text-gray-500 leading-snug">Pesan online, ambil di outlet.</p>
                    </div>
                </div>

                {{-- Dine In --}}
                <div onclick="selectService('dinein')" class="group cursor-pointer bg-white border-2 border-gray-100 hover:border-blue-500 p-6 rounded-3xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-row md:flex-col items-center gap-5 md:gap-2 text-left md:text-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center shadow-sm group-hover:bg-blue-500 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="bi bi-shop-window text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-500 md:mt-4 transition-colors">Dine In</h3>
                        <p class="text-xs text-gray-500 leading-snug">Makan nyaman di tempat kami.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('service-modal');
        const modalContent = document.getElementById('modal-content');
        const labelBtn = document.getElementById('current-service-label');
        const changeBtn = document.getElementById('btn-change-service');

        function showModal() {
            modal.classList.remove('hidden');
            void modal.offsetWidth; 
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-90');
            modalContent.classList.add('scale-100');
        }

        function hideModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-90');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        const currentService = localStorage.getItem('selected_service');
        if (!currentService) {
            showModal();
        } else {
            updateServiceLabel(currentService);
        }

        window.selectService = function(type) {
            localStorage.setItem('selected_service', type);
            hideModal();
            updateServiceLabel(type);
            
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
            })
            Toast.fire({ icon: 'success', title: 'Mode: ' + type.toUpperCase() + ' dipilih' });
        }

        function updateServiceLabel(type) {
            let text = '';
            if(type === 'delivery') text = 'Delivery';
            else if(type === 'takeaway') text = 'Takeaway';
            else text = 'Dine In';

            labelBtn.innerText = text;
            changeBtn.classList.remove('hidden');
        }

        changeBtn.addEventListener('click', showModal);

        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault(); 

            if (!localStorage.getItem('selected_service')) {
                showModal();
                return;
            }

            let form = $(this);
            let btn = form.find('.btn-add');
            let originalHtml = btn.html();

            btn.html('<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>').prop('disabled', true);

            $.ajax({
                url: form.attr('action'), type: 'POST', data: form.serialize(),
                success: function(response) {
                    $('#cart-item-count').text('Cart • ' + response.itemCount + ' item');
                    $('#cart-total-price').text('Rp ' + response.grandTotalFormatted);
                    $('#floating-cart-bar').slideDown();

                    let navbarBadge = $('#navbar-cart-count');
                    if (navbarBadge.length) {
                        navbarBadge.text(response.itemCount);
                        navbarBadge.removeClass('hidden');
                    } else {
                        location.reload(); 
                    }

                    btn.removeClass('bg-gray-900 hover:bg-brand-red').addClass('bg-green-500');
                    btn.html('<i class="bi bi-check-lg text-xl"></i>');

                    setTimeout(() => {
                        btn.removeClass('bg-green-500').addClass('bg-gray-900 hover:bg-brand-red');
                        btn.html(originalHtml).prop('disabled', false);
                    }, 1500);
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menambahkan ke keranjang.', confirmButtonColor: '#E31837' });
                    btn.html(originalHtml).prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush

@endsection