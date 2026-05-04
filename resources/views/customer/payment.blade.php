@extends('layouts.app')

@section('content')

{{-- SETUP PESAN WHATSAPP OTOMATIS --}}
@php
    $adminNumber = '6281216145841'; 
    $menuList = "";
    foreach($order->items as $item) {
        $menuList .= "- " . $item->quantity . "x " . $item->menu_name . "%0A";
    }
    $waMessage = "Halo Admin WontonFactory, saya mau tanya status pesanan:%0A" .
                 "--------------------------------%0A" .
                 "🔢 *No Order:* " . $order->order_number . "%0A" .
                 "👤 *Nama:* " . $order->name . "%0A" .
                 "💳 *Metode:* " . strtoupper($order->payment_method) . " (" . strtoupper($order->service_type) . ")%0A" .
                 "💰 *Total:* Rp " . number_format($order->total_price, 0, ',', '.') . "%0A" .
                 "--------------------------------%0A" .
                 "*Rincian Menu:*%0A" . $menuList . "%0A" .
                 "Mohon diproses ya, Terima kasih!";
@endphp

{{-- HEADER STATUS (DYNAMIC BACKGROUND) --}}
@php
    $statusColor = 'bg-yellow-500'; 
    $statusText = 'Menunggu Pembayaran';
    $statusIcon = 'bi-hourglass-split';
    $statusDesc = 'Mohon selesaikan pembayaran agar pesanan diproses.';

    if($order->status == 'paid') {
        $statusColor = 'bg-blue-500';
        $statusText = 'Pembayaran Diterima';
        $statusIcon = 'bi-check-circle-fill';
        $statusDesc = 'Terima kasih! Pesanan Anda sedang dalam antrian.';
    } elseif($order->status == 'cooking') {
        $statusColor = 'bg-orange-500';
        $statusText = 'Sedang Disiapkan';
        $statusIcon = 'bi-fire';
        $statusDesc = 'Chef kami sedang memasak hidangan lezat untuk Anda.';
    } elseif($order->status == 'completed') {
        $statusColor = 'bg-green-500';
        $statusText = 'Pesanan Selesai';
        $statusIcon = 'bi-bag-check-fill';
        $statusDesc = 'Selamat menikmati hidangan spesial dari WontonFactory!';
    } elseif($order->status == 'cancelled') {
        $statusColor = 'bg-red-600';
        $statusText = 'Pesanan Dibatalkan';
        $statusIcon = 'bi-x-circle-fill';
        $statusDesc = 'Pesanan ini telah dibatalkan.';
    }
@endphp

{{-- 1. HEADER DINAMIS --}}
<div class="relative {{ $statusColor }} text-white pt-28 pb-24 md:pt-40 md:pb-32 overflow-hidden transition-colors duration-500">
    {{-- Pattern Background --}}
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full filter blur-[100px] opacity-20 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full filter blur-[80px] opacity-20 animate-pulse" style="animation-delay: 1s;"></div>

    <div class="container mx-auto px-4 relative z-10 text-center animate-fade-in-up">
        <div class="inline-flex items-center justify-center w-20 h-20 md:w-28 md:h-28 bg-white/20 backdrop-blur-md rounded-full mb-4 md:mb-6 shadow-lg border border-white/30 animate-bounce">
            <i class="bi {{ $statusIcon }} text-3xl md:text-5xl"></i>
        </div>
        <h1 class="text-3xl md:text-6xl font-extrabold mb-3 tracking-tight drop-shadow-md">{{ $statusText }}</h1>
        <p class="text-white/90 text-sm md:text-xl font-medium max-w-3xl mx-auto px-4 leading-relaxed">{{ $statusDesc }}</p>
        
        <div class="mt-6 md:mt-8 inline-block bg-white/20 backdrop-blur px-4 py-1.5 md:px-6 md:py-2 rounded-full border border-white/30 text-xs md:text-base font-mono tracking-wider shadow-sm">
            Order ID: <strong>{{ $order->order_number }}</strong>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 relative -mt-16 md:-mt-24 z-20 pb-24">
    
    {{-- NOTIFIKASI SYSTEM --}}
    @if(session('success'))
        <div class="max-w-5xl mx-auto bg-white border-l-8 border-green-500 p-4 md:p-6 mb-8 rounded-r-2xl shadow-lg animate-fade-in-up flex items-center gap-4 md:gap-6">
            <div class="bg-green-100 p-3 rounded-full text-green-600"><i class="bi bi-check-lg text-2xl"></i></div>
            <div>
                <h4 class="font-bold text-gray-800 text-lg">Berhasil!</h4>
                <p class="text-gray-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($order->status == 'cancelled')
        {{-- TAMPILAN BATAL --}}
        <div class="max-w-3xl mx-auto bg-white rounded-[2.5rem] shadow-xl p-10 md:p-16 text-center border border-red-100 animate-fade-in-up">
            <div class="w-20 h-20 md:w-28 md:h-28 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 md:mb-8">
                <i class="bi bi-emoji-frown text-4xl md:text-6xl"></i>
            </div>
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-3 md:mb-4">Pesanan Telah Dibatalkan</h2>
            <p class="text-gray-500 mb-8 md:mb-10 text-base md:text-lg">Maaf, pesanan ini tidak dapat dilanjutkan.</p>
            <a href="{{ route('menu.index') }}" class="inline-block px-8 py-3 md:px-10 md:py-4 bg-brand-red text-white rounded-full font-bold hover:bg-red-700 transition shadow-lg hover:-translate-y-1 transform">
                Pesan Menu Lain
            </a>
        </div>
    @else
        {{-- TAMPILAN UTAMA (GRID RESPONSIVE) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-10 animate-fade-in-up delay-100 max-w-7xl mx-auto">
            
            {{-- KOLOM KIRI (2/3 LAYAR DESKTOP): STATUS & PEMBAYARAN --}}
            <div class="lg:col-span-2 space-y-8 md:space-y-10 order-2 lg:order-1">
                
                {{-- CARD 1: TRACKING PROGRESS --}}
                <div class="bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] shadow-soft border border-gray-100 relative overflow-hidden">
                    <h3 class="text-lg md:text-2xl font-bold text-gray-800 mb-8 md:mb-12 flex items-center gap-3">
                        <i class="bi bi-activity text-brand-red bg-red-50 p-2 md:p-3 rounded-xl"></i> Lacak Status
                    </h3>

                    {{-- Progress Bar --}}
                    <div class="relative flex justify-between items-center mb-4 md:mb-6 px-2 md:px-4">
                        {{-- Garis Abu (Background) --}}
                        <div class="absolute top-1/2 left-0 w-full h-2 md:h-3 bg-gray-100 -z-10 -translate-y-1/2 rounded-full"></div>
                        {{-- Garis Merah (Active) --}}
                        <div class="absolute top-1/2 left-0 h-2 md:h-3 bg-brand-red -z-10 -translate-y-1/2 rounded-full transition-all duration-1000"
                             style="width: {{ 
                                $order->status == 'pending' ? '15%' : 
                                ($order->status == 'paid' ? '50%' : 
                                ($order->status == 'cooking' ? '80%' : '100%')) 
                             }}">
                        </div>
                        
                        {{-- Steps --}}
                        @foreach([
                            ['status' => 'pending', 'icon' => '1', 'label' => 'Bayar', 'active_color' => 'bg-yellow-500', 'text_color' => 'text-yellow-600'],
                            ['status' => 'paid', 'icon' => '2', 'label' => 'Diterima', 'active_color' => 'bg-blue-500', 'text_color' => 'text-blue-600'],
                            ['status' => 'cooking', 'icon' => '3', 'label' => 'Masak', 'active_color' => 'bg-orange-500', 'text_color' => 'text-orange-600'],
                            ['status' => 'completed', 'icon' => 'bi-flag-fill', 'label' => 'Selesai', 'active_color' => 'bg-green-500', 'text_color' => 'text-green-600']
                        ] as $step)
                            @php
                                $isActive = false;
                                if ($order->status == $step['status']) $isActive = true;
                                // Logika urutan (pending < paid < cooking < completed)
                                $orderStatusVal = ['pending'=>1, 'paid'=>2, 'cooking'=>3, 'completed'=>4, 'cancelled'=>0];
                                $stepVal = ['pending'=>1, 'paid'=>2, 'cooking'=>3, 'completed'=>4];
                                $isPassed = $orderStatusVal[$order->status] >= $stepVal[$step['status']];
                            @endphp

                            <div class="flex flex-col items-center gap-3 relative z-10 group">
                                <div class="w-10 h-10 md:w-14 md:h-14 rounded-full flex items-center justify-center font-bold text-sm md:text-xl transition-all duration-500 border-4 border-white shadow-md
                                    {{ $isActive ? $step['active_color'] . ' text-white ring-4 ring-opacity-30 scale-110' : ($isPassed ? 'bg-brand-red text-white' : 'bg-gray-200 text-gray-400') }}">
                                    @if($isPassed && !$isActive && $step['status'] != 'completed') <i class="bi bi-check-lg"></i> 
                                    @elseif(str_contains($step['icon'], 'bi-')) <i class="bi {{ $step['icon'] }}"></i>
                                    @else {{ $step['icon'] }} @endif
                                </div>
                                <span class="text-[10px] md:text-sm font-bold uppercase tracking-wider {{ $isActive ? $step['text_color'] : 'text-gray-400' }}">{{ $step['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CARD 2: INSTRUKSI & BUKTI --}}
                <div class="bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] shadow-soft border border-gray-100">
                    
                    {{-- TOMBOL WHATSAPP BESAR --}}
                    <div class="mb-10 md:mb-12 text-center bg-green-50 p-6 md:p-8 rounded-[2rem] border border-green-100">
                        <p class="text-sm md:text-base text-green-700 mb-4 font-medium">Butuh bantuan atau ingin konfirmasi cepat?</p>
                        <a href="https://wa.me/{{ $adminNumber }}?text={{ $waMessage }}" target="_blank" 
                           class="inline-flex items-center justify-center w-full md:w-auto px-8 py-4 bg-[#25D366] text-white font-bold text-lg md:text-xl rounded-full shadow-lg hover:shadow-green-200 hover:bg-[#20b858] transition transform hover:-translate-y-1 group">
                            <i class="bi bi-whatsapp text-2xl md:text-3xl me-3 group-hover:animate-bounce"></i> Chat Admin via WhatsApp
                        </a>
                    </div>

                    <div class="border-t border-gray-100 pt-8 md:pt-10">
                        <h3 class="font-bold text-gray-800 mb-6 md:mb-8 flex items-center justify-between text-lg md:text-2xl">
                            <span>Rincian Pembayaran</span>
                            <span class="text-xs md:text-sm font-bold bg-gray-100 px-4 py-1.5 rounded-full text-gray-600 tracking-wide border border-gray-200">{{ strtoupper($order->payment_method) }}</span>
                        </h3>

                        @if($order->status != 'paid' && $order->status != 'completed')
                            
                            <div class="flex flex-col md:flex-row justify-between items-center mb-8 md:mb-10 p-5 md:p-6 bg-gray-50 rounded-2xl md:rounded-3xl border border-gray-200 border-dashed gap-4">
                                <span class="text-sm md:text-base text-gray-600 font-medium">Total Tagihan</span>
                                <span class="text-3xl md:text-4xl font-black text-brand-red">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            @if($order->payment_method == 'qris')
                                {{-- KASUS QRIS --}}
                                @if($order->payment_proof)
                                    {{-- SUDAH UPLOAD --}}
                                    <div class="text-center py-8 md:py-12 bg-green-50 rounded-[2rem] border border-green-100 mb-8">
                                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-full flex items-center justify-center text-green-500 mx-auto mb-4 shadow-sm">
                                            <i class="bi bi-file-earmark-check-fill text-3xl md:text-4xl"></i>
                                        </div>
                                        <h4 class="font-bold text-green-800 text-lg md:text-xl">Bukti Terkirim</h4>
                                        <p class="text-green-600 text-sm md:text-base mb-6">Menunggu konfirmasi admin.</p>
                                        
                                        {{-- PREVIEW BUKTI --}}
                                        <div class="mx-auto max-w-xs rounded-2xl overflow-hidden shadow-lg border-4 border-white group relative">
                                            <img src="{{ asset('storage/' . $order->payment_proof) }}" class="w-full object-cover transform group-hover:scale-105 transition duration-500" alt="Bukti Bayar">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-white font-bold underline text-lg">Lihat Penuh</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ request()->url() }}" class="text-sm md:text-base text-gray-500 hover:text-brand-red font-medium flex items-center justify-center gap-2"><i class="bi bi-arrow-clockwise"></i> Refresh Status</a>
                                    </div>
                                @else
                                    {{-- BELUM UPLOAD --}}
                                    <div class="flex flex-col md:flex-row gap-8 mb-8 items-start">
                                        {{-- QRIS Image --}}
                                        <div class="w-full md:w-1/2 text-center bg-white p-8 rounded-[2rem] border-2 border-dashed border-gray-200 hover:border-brand-red transition cursor-pointer group">
                                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS" class="w-48 h-48 md:w-56 md:h-56 object-contain mx-auto mix-blend-multiply group-hover:scale-105 transition duration-500">
                                            <p class="text-sm text-gray-400 mt-4 font-bold tracking-wide">SCAN QRIS DI ATAS</p>
                                        </div>
                                        
                                        {{-- Form Upload --}}
                                        <form action="{{ route('checkout.upload_proof', $order->id) }}" method="POST" enctype="multipart/form-data" class="w-full md:w-1/2 bg-gray-50 p-6 md:p-8 rounded-[2rem] border border-gray-200 h-full flex flex-col justify-center">
                                            @csrf
                                            <label class="block text-sm font-bold text-gray-700 mb-4">Upload Bukti Transfer</label>
                                            <input type="file" name="payment_proof" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-brand-red file:text-white hover:file:bg-red-700 cursor-pointer mb-6 bg-white rounded-xl border border-gray-200"/>
                                            <button type="submit" class="w-full bg-brand-dark text-white font-bold py-4 rounded-xl hover:bg-brand-red transition shadow-lg text-base">
                                                <i class="bi bi-cloud-upload me-2"></i> Kirim Bukti
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            @else
                                {{-- KASUS CASH --}}
                                <div class="bg-yellow-50 border-l-8 border-yellow-400 p-6 md:p-8 rounded-r-3xl text-yellow-900 flex items-start gap-5">
                                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 shrink-0 text-3xl">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg md:text-xl">Pembayaran Tunai</h4>
                                        <p class="text-sm md:text-base mt-2 text-yellow-800 leading-relaxed">
                                            @if($order->service_type == 'delivery')
                                                Mohon siapkan uang pas sebesar total tagihan saat kurir kami sampai di lokasi Anda.
                                            @else
                                                Silakan lakukan pembayaran langsung di meja kasir setelah pesanan selesai/diambil.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                        @else
                            {{-- SUDAH LUNAS --}}
                            <div class="text-center py-12 md:py-16 bg-green-50 rounded-[3rem] border border-green-100">
                                <div class="w-24 h-24 bg-white text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md animate-bounce">
                                    <i class="bi bi-check-lg text-5xl"></i>
                                </div>
                                <h4 class="font-bold text-2xl md:text-3xl text-green-800">Lunas!</h4>
                                <p class="text-green-600 text-base md:text-lg">Transaksi Anda telah diselesaikan.</p>
                            </div>
                        @endif

                        {{-- TOMBOL BATALKAN --}}
                        @if($order->status != 'completed' && $order->status != 'paid' && $order->status != 'cancelled' && !$order->cancellation_note)
                            <div class="mt-12 pt-8 border-t border-gray-100 text-center">
                                <button type="button" onclick="openCancelModal()" class="text-gray-400 text-sm md:text-base font-bold hover:text-red-500 transition flex items-center justify-center gap-2 mx-auto group py-2 px-4 rounded-lg hover:bg-red-50">
                                    <i class="bi bi-x-circle group-hover:rotate-90 transition-transform text-lg"></i> Batalkan Pesanan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (1/3 LAYAR DESKTOP): RINGKASAN MENU (STICKY) --}}
            <div class="lg:col-span-1 animate-fade-in-up delay-200 order-1 lg:order-2">
                {{-- STICKY WRAPPER --}}
                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[3rem] shadow-2xl border-t-8 border-brand-red sticky top-28">
                    <h3 class="text-lg md:text-xl font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4 flex justify-between items-center">
                        <span>Menu Dipesan</span>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $order->items->count() }} Item</span>
                    </h3>
                    
                    <div class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($order->items as $item)
                        <div class="flex gap-4 items-start">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl flex-shrink-0 flex items-center justify-center text-gray-300 overflow-hidden border border-gray-100 shadow-sm">
                                <i class="bi bi-bag-fill text-2xl md:text-3xl"></i>
                            </div>
                            <div class="flex-grow pt-1">
                                <p class="font-bold text-gray-800 text-sm md:text-base leading-tight mb-1">{{ $item->menu_name }}</p>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded">{{ $item->quantity }}x</p>
                                    <p class="text-brand-red font-extrabold text-sm md:text-base">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-6">
                         <div class="flex justify-between items-center text-sm text-gray-500 mb-2">
                            <span>Metode Layanan</span>
                            <span class="font-bold text-gray-800 uppercase bg-gray-100 px-2 rounded">{{ $order->service_type }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg md:text-xl font-black text-gray-900 mt-4 pt-4 border-t border-gray-100">
                            <span>Total</span>
                            <span class="text-brand-red">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('menu.index') }}" class="block w-full py-4 bg-gray-50 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 hover:text-brand-red transition text-center text-sm border border-gray-200">
                            <i class="bi bi-plus-circle-dotted me-2"></i> Tambah Menu Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL BATAL --}}
        <div id="cancelModal" class="fixed inset-0 z-[9999] bg-brand-dark/90 backdrop-blur-sm flex items-center justify-center p-4 hidden transition-opacity duration-300 opacity-0 pointer-events-none">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-8 md:p-10 transform scale-90 transition-transform duration-300 pointer-events-auto">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <i class="bi bi-exclamation-triangle-fill text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Batalkan Pesanan?</h3>
                    <p class="text-gray-500 text-sm mt-2">Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                </div>
                
                <form action="{{ route('checkout.cancel', $order->id) }}" method="POST">
                    @csrf
                    @if(($order->status == 'pending' && !$order->payment_proof && $order->payment_method == 'qris') || ($order->status == 'pending' && $order->payment_method != 'qris'))
                        <p class="text-gray-600 mb-8 text-center bg-gray-50 p-4 rounded-xl text-sm border border-gray-100">
                            Pesanan belum diproses. Akan langsung dibatalkan secara otomatis.
                        </p>
                        <div class="flex gap-4">
                            <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Kembali</button>
                            <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200">Ya, Batalkan</button>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-sm text-yellow-800 mb-6 flex gap-3 items-start">
                            <i class="bi bi-info-circle-fill text-lg flex-shrink-0 mt-0.5"></i>
                            <span>Pesanan sedang diproses/sudah dibayar. Pembatalan memerlukan persetujuan Admin.</span>
                        </div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Alasan Pembatalan</label>
                        <textarea name="reason" rows="3" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm mb-6 resize-none" placeholder="Contoh: Salah pilih menu..."></textarea>
                        
                        <div class="flex gap-4">
                            <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Kembali</button>
                            <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200">Ajukan</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <script>
            const modal = document.getElementById('cancelModal');
            function openCancelModal() {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('div').classList.remove('scale-90');
                    modal.querySelector('div').classList.add('scale-100');
                }, 10);
            }
            function closeCancelModal() {
                modal.classList.add('opacity-0');
                modal.querySelector('div').classList.remove('scale-100');
                modal.querySelector('div').classList.add('scale-90');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        </script>
    @endif

</div>

{{-- ANIMASI CSS TAMBAHAN --}}
<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow { animation: bounce-slow 3s infinite; }
</style>

@endsection