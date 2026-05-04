@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 flex justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-lg w-full border-t-8 border-pizza-red text-center">
        
        {{-- Ikon Sukses --}}
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Pesanan Diterima!</h2>
        <p class="text-gray-500 mb-8">Terima kasih, pesanan Anda telah masuk ke sistem kami.</p>

        {{-- Detail Info --}}
        <div class="bg-light-grey p-6 rounded-xl text-left mb-8 border border-gray-200">
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Nomor Order:</span>
                <span class="font-bold text-dark-grey">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Layanan:</span>
                <span class="font-bold text-pizza-red uppercase">{{ $order->service_type }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Metode Bayar:</span>
                <span class="font-bold text-dark-grey uppercase">
                    {{ $order->payment_method == 'cash' ? 'TUNAI / CASH' : 'QRIS / TRANSFER' }}
                </span>
            </div>
            
            <div class="border-t border-dashed border-gray-300 my-4"></div>

            <div class="flex justify-between text-xl items-center">
                <span class="font-bold text-gray-800">Total Tagihan:</span>
                <span class="font-extrabold text-pizza-red">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- === AREA LOGIKA INSTRUKSI PEMBAYARAN === --}}
        <div class="space-y-4">

            {{-- JIKA CASH (TUNAI/COD) --}}
            @if($order->payment_method == 'cash')

                @if($order->service_type == 'delivery')
                    {{-- KASUS COD --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-left">
                        <p class="text-sm text-orange-800 font-bold mb-1"><i class="bi bi-truck me-1"></i> Bayar di Tempat (COD)</p>
                        <p class="text-xs text-orange-700">
                            Pesanan akan diantar. Mohon siapkan uang pas sebesar 
                            <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> untuk kurir.
                        </p>
                    </div>
                @else
                    {{-- KASUS BAYAR DI KASIR (Dine In / Takeaway) --}}
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left">
                        <p class="text-sm text-yellow-800 font-bold mb-1"><i class="bi bi-cash-stack me-1"></i> Menunggu Pembayaran</p>
                        <p class="text-xs text-yellow-700">
                            Silakan menuju <strong>Meja Kasir</strong> dan sebutkan Nomor Order 
                            <strong>#{{ $order->order_number }}</strong> untuk melakukan pembayaran.
                        </p>
                    </div>
                @endif

            {{-- JIKA QRIS (ATAU DEFAULT) --}}
            @else
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                    <p class="text-sm text-blue-800 font-semibold mb-1"><i class="bi bi-qr-code me-1"></i> Pembayaran QRIS:</p>
                    <p class="text-xs text-blue-600">Mohon kirimkan bukti transfer agar pesanan segera diproses.</p>
                </div>

                {{-- Tombol WA Konfirmasi --}}
                <a href="https://wa.me/6285859742189?text=Halo%20Admin,%20saya%20sudah%20bayar%20pesanan%20ID:%20{{ $order->order_number }}%20via%20QRIS" 
                   target="_blank"
                   class="flex items-center justify-center w-full py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition shadow-md gap-2">
                    <i class="bi bi-whatsapp"></i> Konfirmasi Bukti Bayar ke WA
                </a>

            @endif

            {{-- Tombol Kembali --}}
            <a href="{{ route('menu.index') }}" class="block w-full py-3 bg-white border-2 border-pizza-red text-pizza-red font-bold rounded-lg hover:bg-red-50 transition text-center mt-4">
                Pesan Lagi / Kembali ke Menu
            </a>
        </div>

    </div>
</div>
@endsection