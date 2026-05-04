@extends('layouts.app')

@section('content')

{{-- 1. HEADER PROFIL (CLEAN & MODERN) --}}
<div class="relative bg-white border-b border-gray-200 pt-32 pb-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 animate-fade-in-up">
            
            {{-- FOTO PROFIL --}}
            <div class="relative group shrink-0">
                <div class="w-32 h-32 rounded-full p-1 bg-white shadow-lg border border-gray-100 overflow-hidden relative">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover rounded-full">
                    @else
                        <div class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-5xl">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                    
                    {{-- Overlay Edit --}}
                    <button onclick="document.getElementById('photo-upload-input').click()" 
                         class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 rounded-full cursor-pointer">
                        <i class="bi bi-camera-fill text-white text-2xl"></i>
                    </button>
                </div>

                <form id="photo-upload-form" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="photo" id="photo-upload-input" accept="image/*" onchange="document.getElementById('photo-upload-form').submit()">
                </form>
            </div>

            {{-- INFO USER --}}
            <div class="text-center md:text-left flex-grow">
                <div class="flex flex-col md:flex-row items-center md:items-baseline gap-3 mb-2">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                    <span class="bg-brand-red/10 text-brand-red text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Member Setia</span>
                </div>
                <p class="text-gray-500 font-medium mb-6">{{ $user->email }}</p>
                
                {{-- Statistik --}}
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <div class="bg-gray-50 px-5 py-3 rounded-xl border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-brand-red shadow-sm border border-gray-100">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-gray-400 font-bold uppercase">Total Pesanan</p>
                            <p class="text-gray-900 font-bold">{{ $orders->count() }}x Order</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3 rounded-xl border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-500 shadow-sm border border-gray-100">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-gray-400 font-bold uppercase">Bergabung</p>
                            <p class="text-gray-900 font-bold">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    
    {{-- NOTIFIKASI --}}
    @if (session('status'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-r-lg shadow-sm flex items-center gap-3 animate-fade-in-up">
            <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
            <p class="text-green-800 font-medium">{{ session('status') }}</p>
        </div>
    @endif
    @if ($errors->has('photo'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-lg shadow-sm flex items-center gap-3 animate-fade-in-up">
            <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
            <p class="text-red-800 font-medium">{{ $errors->first('photo') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- KOLOM KIRI: RIWAYAT PESANAN --}}
        <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100">
            
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Pesanan</h3>
                <a href="{{ route('menu.index') }}" class="text-sm font-bold text-brand-red hover:text-red-700 transition">
                    + Pesan Baru
                </a>
            </div>

            @forelse($orders as $order)
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                    
                    {{-- Status Line --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 
                        {{ $order->status == 'pending' ? 'bg-yellow-500' : 
                          ($order->status == 'paid' ? 'bg-blue-500' : 
                          ($order->status == 'cooking' ? 'bg-orange-500' : 
                          ($order->status == 'completed' ? 'bg-green-500' : 'bg-red-500'))) }}">
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-6 pl-4">
                        {{-- Info --}}
                        <div class="flex-grow">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-mono text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded">#{{ $order->order_number }}</span>
                                <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            
                            <div class="flex items-baseline gap-2 mb-3">
                                <span class="text-xl font-extrabold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-3">
                                {{-- Status Badge --}}
                                @php
                                    $badgeClass = 'bg-gray-100 text-gray-600';
                                    $icon = 'bi-circle';
                                    if($order->status == 'pending') { $badgeClass = 'bg-yellow-50 text-yellow-700 border-yellow-100'; $icon = 'bi-hourglass-split'; }
                                    elseif($order->status == 'paid') { $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100'; $icon = 'bi-check-circle'; }
                                    elseif($order->status == 'cooking') { $badgeClass = 'bg-orange-50 text-orange-700 border-orange-100'; $icon = 'bi-fire'; }
                                    elseif($order->status == 'completed') { $badgeClass = 'bg-green-50 text-green-700 border-green-100'; $icon = 'bi-bag-check-fill'; }
                                    elseif($order->status == 'cancelled') { $badgeClass = 'bg-red-50 text-red-700 border-red-100'; $icon = 'bi-x-circle'; }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                    <i class="bi {{ $icon }} me-1"></i> {{ $order->status }}
                                </span>
                                
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-500 border border-gray-200 uppercase">
                                    {{ $order->service_type }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 line-clamp-1">
                                <span class="font-medium text-gray-700">Menu:</span>
                                @foreach($order->items as $item)
                                    {{ $item->menu_name }} ({{ $item->quantity }})@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex items-center min-w-[120px]">
                            @if($order->status == 'pending')
                                <a href="{{ route('checkout.payment', $order->id) }}" class="w-full py-2.5 bg-brand-red text-white text-sm font-bold rounded-xl hover:bg-red-700 transition text-center shadow-sm">
                                    Bayar
                                </a>
                            @else
                                <a href="{{ route('checkout.payment', $order->id) }}" class="w-full py-2.5 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition text-center">
                                    Detail
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-2xl p-10 text-center border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 border border-gray-200">
                        <i class="bi bi-cart-x text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Belum ada pesanan</h3>
                    <p class="text-sm text-gray-500 mb-6">Yuk, mulai pesan makanan favoritmu!</p>
                    <a href="{{ route('menu.index') }}" class="inline-block px-6 py-2.5 bg-brand-red text-white rounded-full text-sm font-bold hover:bg-red-700 transition shadow-sm">
                        Mulai Pesan
                    </a>
                </div>
            @endforelse
        </div>

        {{-- KOLOM KANAN: PENGATURAN --}}
        <div class="space-y-8 animate-fade-in-up delay-200">
            
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-6">Pengaturan Akun</h3>

            {{-- 1. EDIT PROFIL --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="bi bi-person-gear text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Data Diri</h4>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- 2. KEAMANAN --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="bi bi-shield-lock text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Keamanan</h4>
                </div>
                @include('profile.partials.update-password-form')
            </div>

            {{-- 3. HAPUS AKUN --}}
            <div class="bg-white p-6 rounded-2xl border border-red-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Zona Bahaya</h4>
                </div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection