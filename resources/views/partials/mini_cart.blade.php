{{-- resources/views/partials/mini_cart.blade.php --}}

@php
    $itemCount = $cartData['itemCount'] ?? 0;
    $grandTotal = $cartData['grandTotal'] ?? 0;
    $grandTotalFormatted = number_format($grandTotal, 0, ',', '.');
@endphp

{{-- [UPDATE] Hapus bottom-0 di sini. Posisi Bottom akan diatur oleh JavaScript --}}
<div id="floating-cart-bar" class="fixed left-0 right-0 p-4" 
    style="display: {{ $itemCount > 0 ? 'block' : 'none' }}; z-index: 1050;"> 
    
    <div class="container mx-auto max-w-4xl">
        <a href="{{ route('checkout.index') }}" class="flex justify-between items-center 
           bg-red-700 text-white text-lg font-bold py-3 px-6 rounded-full shadow-2xl 
           hover:bg-red-800 transition">
            
            {{-- Kiri: Cart Icon & Item Count --}}
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                <span id="cart-item-count">Cart • {{ $itemCount }} item</span>
            </div>

            {{-- Kanan: Total Harga --}}
            <strong id="cart-total-price">Rp {{ $grandTotalFormatted }}</strong>
        </a>
    </div>
</div>