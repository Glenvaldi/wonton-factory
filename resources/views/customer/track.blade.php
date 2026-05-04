@extends('layouts.app')

@section('content')

{{-- 1. HEADER SECTION --}}
<div class="relative bg-brand-dark text-white pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-red rounded-full filter blur-[100px] opacity-30"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="text-brand-red font-bold tracking-[0.2em] uppercase text-xs md:text-sm mb-2 block animate-fade-in-up">Order Tracking</span>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 tracking-tight animate-fade-in-up">
            Lacak <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500">Pesananmu</span>
        </h1>
        <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto animate-fade-in-up delay-100">
            Pantau proses pesanan WontonFactory Anda secara real-time di sini.
        </p>
    </div>
</div>

<div class="container mx-auto px-4 relative -mt-10 z-20 pb-20">
    
    <div class="max-w-2xl mx-auto">
        
        {{-- 2. FORM PENCARIAN (GLASSMORPHISM) --}}
        <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-gray-100 animate-fade-in-up delay-200">
            <form action="{{ route('track.search') }}" method="GET" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input Order ID --}}
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nomor Order</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-hash text-brand-red"></i>
                            </div>
                            <input type="text" name="order_number" required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-bold text-gray-800 placeholder-gray-400 uppercase" 
                                placeholder="ORD-XXXXX" value="{{ request('order_number') }}">
                        </div>
                    </div>

                    {{-- Input No HP --}}
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-whatsapp text-green-500"></i>
                            </div>
                            <input type="text" name="phone" required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-bold text-gray-800 placeholder-gray-400" 
                                placeholder="08xxxxxxxx" value="{{ request('phone') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-brand-red text-white font-bold rounded-xl shadow-glow hover:bg-red-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i> Cek Status Sekarang
                </button>
            </form>

            {{-- PESAN ERROR --}}
            @if(session('error'))
                <div class="mt-6 bg-red-50 border-l-4 border-brand-red p-4 rounded-r-lg flex items-center gap-3 animate-pulse">
                    <i class="bi bi-x-circle-fill text-brand-red text-xl"></i>
                    <p class="text-sm text-red-800 font-bold">{{ session('error') }}</p>
                </div>
            @endif
        </div>

        {{-- 3. HASIL PENCARIAN --}}
        @if(isset($order))
        <div class="mt-8 bg-white shadow-2xl rounded-[2.5rem] overflow-hidden animate-fade-in-up border border-gray-100 relative">
            
            {{-- Status Badge Header --}}
            @php
                $statusColor = 'bg-gray-500';
                $statusText = 'Tidak Diketahui';
                
                if($order->status == 'pending') { $statusColor = 'bg-yellow-500'; $statusText = 'Menunggu Pembayaran'; }
                elseif($order->status == 'paid') { $statusColor = 'bg-blue-500'; $statusText = 'Pembayaran Diterima'; }
                elseif($order->status == 'cooking') { $statusColor = 'bg-orange-500'; $statusText = 'Sedang Dimasak'; }
                elseif($order->status == 'completed') { $statusColor = 'bg-green-500'; $statusText = 'Selesai / Diantar'; }
                elseif($order->status == 'cancelled') { $statusColor = 'bg-red-600'; $statusText = 'Dibatalkan'; }
            @endphp

            <div class="{{ $statusColor }} px-8 py-6 text-white flex flex-col md:flex-row justify-between items-center relative overflow-hidden gap-4 text-center md:text-left">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="relative z-10">
                    <p class="text-xs text-white/80 uppercase font-bold tracking-wider mb-1">Status Pesanan</p>
                    <h3 class="text-2xl font-extrabold">{{ $statusText }}</h3>
                </div>
                <div class="relative z-10 bg-white/20 backdrop-blur-md px-4 py-2 rounded-lg border border-white/30">
                    <span class="font-mono font-bold text-lg">#{{ $order->order_number }}</span>
                </div>
            </div>

            <div class="p-8">
                {{-- TIMELINE STATUS --}}
                @if($order->status != 'cancelled')
                <div class="relative flex justify-between items-center mb-10 mt-4">
                    {{-- Garis --}}
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -z-10 -translate-y-1/2 rounded-full"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-brand-red -z-10 -translate-y-1/2 rounded-full transition-all duration-1000"
                         style="width: {{ 
                            $order->status == 'pending' ? '15%' : 
                            ($order->status == 'paid' ? '50%' : 
                            ($order->status == 'cooking' ? '80%' : '100%')) 
                         }}">
                    </div>
                    
                    {{-- Step 1: Bayar (Pending) --}}
                    <div class="flex flex-col items-center gap-2 bg-white px-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-all border-2 border-white shadow-sm
                            {{ $order->status == 'pending' ? 'bg-yellow-500 text-white ring-4 ring-yellow-100' : 'bg-brand-red text-white' }}">
                            @if($order->status == 'pending') 1 @else <i class="bi bi-check-lg"></i> @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase text-gray-500">Bayar</span>
                    </div>

                    {{-- Step 2: Diterima (Paid) --}}
                    <div class="flex flex-col items-center gap-2 bg-white px-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-all border-2 border-white shadow-sm
                            {{ $order->status == 'paid' ? 'bg-blue-500 text-white ring-4 ring-blue-100' : ($order->status == 'cooking' || $order->status == 'completed' ? 'bg-brand-red text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($order->status == 'paid') 2 @elseif($order->status == 'cooking' || $order->status == 'completed') <i class="bi bi-check-lg"></i> @else 2 @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase text-gray-500">Proses</span>
                    </div>

                    {{-- Step 3: Masak (Cooking) --}}
                    <div class="flex flex-col items-center gap-2 bg-white px-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-all border-2 border-white shadow-sm
                            {{ $order->status == 'cooking' ? 'bg-orange-500 text-white ring-4 ring-orange-100' : ($order->status == 'completed' ? 'bg-brand-red text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($order->status == 'cooking') <i class="bi bi-fire"></i> @elseif($order->status == 'completed') <i class="bi bi-check-lg"></i> @else 3 @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase text-gray-500">Masak</span>
                    </div>

                    {{-- Step 4: Selesai (Completed) --}}
                    <div class="flex flex-col items-center gap-2 bg-white px-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-all border-2 border-white shadow-sm
                            {{ $order->status == 'completed' ? 'bg-green-500 text-white ring-4 ring-green-100' : 'bg-gray-200 text-gray-400' }}">
                            @if($order->status == 'completed') <i class="bi bi-flag-fill"></i> @else 4 @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase text-gray-500">Selesai</span>
                    </div>
                </div>
                @endif

                {{-- Rincian Menu --}}
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex justify-between">
                        <span>Rincian Menu</span>
                        <span class="text-brand-red">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </h4>
                    <ul class="space-y-3">
                        @foreach($order->items as $item)
                        <li class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-gray-600 font-bold px-2 py-1 rounded shadow-sm text-xs">{{ $item->quantity }}x</span>
                                <span class="font-bold text-gray-800">{{ $item->menu_name }}</span>
                            </div>
                            <span class="font-bold text-brand-red">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-dashed border-gray-300 mt-4 pt-4 flex justify-between items-center">
                        <span class="font-bold text-gray-800 text-lg">Total Bayar</span>
                        <span class="font-black text-xl text-brand-red">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                @if($order->status != 'completed' && $order->status != 'cancelled')
                    <div class="mt-6 text-center">
                        <a href="{{ route('checkout.payment', $order->id) }}" class="text-sm font-bold text-gray-400 hover:text-brand-red underline decoration-2 underline-offset-4 transition">
                            Lihat Halaman Pembayaran / Upload Bukti
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endsection