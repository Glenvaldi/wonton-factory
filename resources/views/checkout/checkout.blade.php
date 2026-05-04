@extends('layouts.app')

@section('content')

{{-- 1. HEADER SECTION MOBILE --}}
<div class="relative bg-brand-dark text-white pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute -bottom-24 -right-24 w-64 h-64 md:w-96 md:h-96 bg-brand-red rounded-full filter blur-[80px] md:blur-[100px] opacity-30"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="text-brand-red font-bold tracking-[0.2em] uppercase text-[10px] md:text-sm mb-2 block animate-fade-in-up">Final Step</span>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-3 tracking-tight animate-fade-in-up">
            Selesaikan <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500">Pesanan</span>
        </h1>
        <p class="text-gray-400 text-xs md:text-base max-w-xl mx-auto animate-fade-in-up delay-100">
            Lengkapi data diri Anda untuk memproses pesanan lezat ini.
        </p>
    </div>
</div>

<div class="container mx-auto px-4 relative -mt-10 md:-mt-16 z-20 pb-20">

    {{-- ALERT ERROR SYSTEM --}}
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-brand-red p-4 mb-6 rounded-r-xl shadow-lg animate-fade-in-up">
            <div class="flex items-start">
                <i class="bi bi-x-circle-fill text-brand-red text-xl flex-shrink-0"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Terjadi Kesalahan</h3>
                    <p class="text-xs md:text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-brand-red p-4 mb-6 rounded-r-xl shadow-lg animate-fade-in-up">
            <div class="flex">
                <i class="bi bi-exclamation-triangle-fill text-brand-red flex-shrink-0"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Periksa Kembali</h3>
                    <ul class="list-disc list-inside text-xs md:text-sm text-red-700 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div id="checkout-content-wrapper">
        @if(!$cart || count($cart) == 0)
            {{-- KERANJANG KOSONG --}}
            <div class="bg-white rounded-3xl md:rounded-[2rem] shadow-xl p-8 md:p-12 text-center border border-gray-100 animate-fade-in-up">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="bi bi-cart-x text-4xl md:text-5xl text-brand-red/50"></i>
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Keranjang Kosong</h2>
                <p class="text-sm md:text-base text-gray-500 mb-8">Sepertinya Anda belum memilih menu apapun.</p>
                <a href="{{ route('menu.index') }}" class="inline-block px-8 py-3 bg-brand-red text-white rounded-full font-bold hover:bg-red-700 transition shadow-lg hover:-translate-y-1 text-sm md:text-base">
                    Mulai Pesan
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                {{-- KOLOM KIRI: FORM DATA DIRI --}}
                <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100 order-2 lg:order-1">
                    
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        {{-- 1. KARTU LAYANAN --}}
                        <div class="bg-white p-5 md:p-8 rounded-3xl md:rounded-[2rem] shadow-soft border border-gray-100">
                            <h3 class="text-base md:text-lg font-bold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-brand-red text-white flex items-center justify-center text-[10px] md:text-xs">1</span>
                                Mode Layanan
                            </h3>
                            
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                                <label class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tipe Pesanan</label>
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div id="service-icon-display" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl flex items-center justify-center text-brand-red text-xl md:text-2xl shadow-sm border border-gray-100 shrink-0">
                                        <i class="bi bi-shop"></i> 
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <select name="service_type" id="service-type-select" class="w-full bg-transparent border-none text-gray-800 font-bold text-sm md:text-lg focus:ring-0 cursor-pointer py-1 px-0 truncate">
                                            <option value="dinein">Makan di Tempat (Dine In)</option>
                                            <option value="takeaway">Ambil Sendiri (Takeaway)</option>
                                            <option value="delivery">Diantar Kurir (Delivery)</option>
                                        </select>
                                        <p class="text-[10px] md:text-xs text-gray-500 truncate">Klik untuk mengubah layanan.</p>
                                    </div>
                                    <i class="bi bi-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        {{-- 2. KARTU DATA PELANGGAN --}}
                        <div class="bg-white p-5 md:p-8 rounded-3xl md:rounded-[2rem] shadow-soft border border-gray-100 mt-4 md:mt-6">
                            <h3 class="text-base md:text-lg font-bold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-brand-red text-white flex items-center justify-center text-[10px] md:text-xs">2</span>
                                Informasi Pemesan
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
                                <div>
                                    <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nama Lengkap</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="bi bi-person text-gray-400 group-focus-within:text-brand-red transition"></i>
                                        </div>
                                        <input type="text" name="name" required 
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-medium text-gray-800 placeholder-gray-400 text-sm"
                                               placeholder="Nama Anda" 
                                               value="{{ Auth::user()->name ?? old('name') }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="bi bi-whatsapp text-gray-400 group-focus-within:text-green-500 transition"></i>
                                        </div>
                                        <input type="tel" name="phone" required 
                                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-medium text-gray-800 placeholder-gray-400 text-sm"
                                               placeholder="08xxxxxxxx" 
                                               value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- INPUT DINAMIS --}}
                            <div class="hidden transition-all duration-300" id="address-field-container"> 
                                <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Alamat Lengkap</label>
                                <div class="relative group">
                                    <div class="absolute top-3 left-4 pointer-events-none">
                                        <i class="bi bi-geo-alt text-gray-400 group-focus-within:text-brand-red transition"></i>
                                    </div>
                                    <textarea id="address" name="address" rows="3" 
                                              class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-medium text-gray-800 placeholder-gray-400 text-sm"
                                              placeholder="Nama Jalan, No Rumah, Patokan...">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="hidden transition-all duration-300" id="table-field-container"> 
                                <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nomor Meja</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-hash text-gray-400 group-focus-within:text-brand-red transition"></i>
                                    </div>
                                    <select id="table_number" name="table_number" 
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition font-medium text-gray-800 cursor-pointer appearance-none text-sm">
                                        <option value="" selected disabled>-- Pilih Meja --</option>
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="Meja {{ $i }}" {{ old('table_number') == "Meja $i" ? 'selected' : '' }}>Meja {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. PEMBAYARAN --}}
                        <div class="bg-white p-5 md:p-8 rounded-3xl md:rounded-[2rem] shadow-soft border border-gray-100 mt-4 md:mt-6">
                            <h3 class="text-base md:text-lg font-bold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-brand-red text-white flex items-center justify-center text-[10px] md:text-xs">3</span>
                                Metode Pembayaran
                            </h3>
                            <div class="space-y-3" id="payment-options-container">
                                {{-- JS --}}
                            </div>
                        </div>

                    </form> 
                    
                    {{-- BUTTON BACK --}}
                    <a href="{{ route('menu.index') }}" class="inline-block w-full text-center mt-2 py-3 border-2 border-gray-200 text-gray-500 font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition text-sm">
                        <i class="bi bi-arrow-left me-2"></i> Pesan Menu Lain
                    </a>

                </div>

                {{-- KOLOM KANAN RINGKASAN --}}
                <div class="lg:col-span-1 animate-fade-in-up delay-200 order-1 lg:order-2">
                    <div class="bg-white p-5 md:p-8 rounded-3xl md:rounded-[2rem] shadow-2xl border-t-8 border-brand-red sticky top-24">
                        <h3 class="text-lg md:text-xl font-extrabold text-gray-900 mb-4 md:mb-6 border-b border-gray-100 pb-3 md:pb-4">Ringkasan</h3>
                        
                        <div class="space-y-4 md:space-y-5 mb-6 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($cart as $id => $item)
                            <div class="flex gap-3 md:gap-4 items-start relative" id="cart-row-{{ $id }}">
                                {{-- Gambar Kecil --}}
                                <div class="w-14 h-14 md:w-16 md:h-16 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-100">
                                    @if(isset($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="bi bi-image"></i></div>
                                    @endif
                                </div>

                                <div class="flex-grow">
                                    <h4 class="font-bold text-gray-800 text-xs md:text-sm leading-snug mb-1 line-clamp-1">{{ $item['name'] }}</h4>
                                    <p class="text-brand-red font-bold text-xs" id="subtotal-{{ $id }}">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>

                                    {{-- Kontrol Qty (TOUCH FRIENDLY) --}}
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 p-0.5">
                                            <button type="button" onclick="updateQty('{{ $id }}', 'decrease')" class="w-8 h-8 md:w-6 md:h-6 flex items-center justify-center bg-white text-gray-500 rounded-md shadow-sm hover:text-red-600 transition text-sm">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="w-8 text-center text-xs font-bold text-gray-700" id="qty-{{ $id }}">{{ $item['quantity'] }}</span>
                                            <button type="button" onclick="updateQty('{{ $id }}', 'increase')" class="w-8 h-8 md:w-6 md:h-6 flex items-center justify-center bg-white text-gray-500 rounded-md shadow-sm hover:text-green-600 transition text-sm">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>

                                        <button type="button" onclick="removeItem('{{ $id }}')" class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-red-500 transition text-sm" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-dashed border-gray-300 pt-4 mt-auto">
                            <div class="flex justify-between items-center text-gray-500 text-xs md:text-sm mb-2">
                                <span>Subtotal</span>
                                <span class="font-bold text-gray-800">Rp {{ $cartData['grandTotalFormatted'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-lg md:text-xl font-black text-brand-red">
                                <span>Total Bayar</span>
                                <span id="grand-total-display">Rp {{ $cartData['grandTotalFormatted'] }}</span>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" class="w-full mt-6 py-3.5 md:py-4 bg-brand-red text-white font-bold rounded-xl md:rounded-2xl shadow-glow hover:bg-red-700 hover:shadow-lg transition transform hover:-translate-y-1 flex justify-center items-center gap-3 group text-sm md:text-base">
                            <span>PROSES PESANAN</span> 
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white group-hover:text-brand-red transition">
                                <i class="bi bi-arrow-right text-xs"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service-type-select');
    const serviceIcon = document.getElementById('service-icon-display');
    const paymentContainer = document.getElementById('payment-options-container');
    const addressContainer = document.getElementById('address-field-container');
    const tableContainer = document.getElementById('table-field-container');
    const addressInput = document.getElementById('address');
    const tableInput = document.getElementById('table_number');

    const savedService = localStorage.getItem('selected_service');
    if(savedService && ['dinein', 'takeaway', 'delivery'].includes(savedService)) {
        if(serviceSelect) serviceSelect.value = savedService;
    }

    function updateCheckoutForm() {
        if(!serviceSelect) return;
        const type = serviceSelect.value;
        let optionsHtml = '';

        addressInput.required = false;
        tableInput.required = false;
        addressContainer.classList.add('hidden');
        tableContainer.classList.add('hidden');

        // Style untuk opsi radio button di mobile
        const radioClass = "flex items-center p-3 md:p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-red-50 hover:border-red-200 transition group mb-3";
        const iconContainerClass = "w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-brand-red shadow-sm group-hover:scale-110 transition shrink-0";
        const textContainerClass = "ml-3 md:ml-4 flex-grow";
        const titleClass = "block font-bold text-gray-800 text-xs md:text-sm";
        const subClass = "text-[10px] md:text-xs text-gray-500";

        if (type === 'delivery') {
            serviceIcon.innerHTML = '<i class="bi bi-truck"></i>';
            addressContainer.classList.remove('hidden');
            addressInput.required = true;
            optionsHtml += `
            <label class="${radioClass}">
                <div class="${iconContainerClass}"><i class="bi bi-cash"></i></div>
                <div class="${textContainerClass}">
                    <span class="${titleClass}">Bayar di Tempat (COD)</span>
                    <span class="${subClass}">Bayar tunai saat kurir sampai</span>
                </div>
                <input type="radio" name="payment_method" value="cash" class="w-4 h-4 md:w-5 md:h-5 text-brand-red focus:ring-brand-red border-gray-300" checked>
            </label>`;
        } else if (type === 'dinein') {
            serviceIcon.innerHTML = '<i class="bi bi-shop"></i>';
            tableContainer.classList.remove('hidden');
            tableInput.required = true;
            optionsHtml += `
            <label class="${radioClass}">
                <div class="${iconContainerClass}"><i class="bi bi-cash-coin"></i></div>
                <div class="${textContainerClass}">
                    <span class="${titleClass}">Bayar di Kasir</span>
                    <span class="${subClass}">Bayar setelah makan</span>
                </div>
                <input type="radio" name="payment_method" value="cash" class="w-4 h-4 md:w-5 md:h-5 text-brand-red focus:ring-brand-red border-gray-300" checked>
            </label>`;
        } else {
            serviceIcon.innerHTML = '<i class="bi bi-bag-check"></i>';
            optionsHtml += `
            <label class="${radioClass}">
                <div class="${iconContainerClass}"><i class="bi bi-wallet2"></i></div>
                <div class="${textContainerClass}">
                    <span class="${titleClass}">Bayar Saat Ambil</span>
                    <span class="${subClass}">Bayar tunai di outlet</span>
                </div>
                <input type="radio" name="payment_method" value="cash" class="w-4 h-4 md:w-5 md:h-5 text-brand-red focus:ring-brand-red border-gray-300" checked>
            </label>`;
        }

        optionsHtml += `
        <label class="${radioClass}">
            <div class="${iconContainerClass}"><i class="bi bi-qr-code"></i></div>
            <div class="${textContainerClass}">
                <span class="${titleClass}">QRIS (Scan)</span>
                <span class="${subClass}">GoPay, OVO, Dana, ShopeePay</span>
            </div>
            <input type="radio" name="payment_method" value="qris" class="w-4 h-4 md:w-5 md:h-5 text-brand-red focus:ring-brand-red border-gray-300">
        </label>`;
        paymentContainer.innerHTML = optionsHtml;
    }

    if(serviceSelect) {
        updateCheckoutForm();
        serviceSelect.addEventListener('change', updateCheckoutForm);
    }
});

function updateQty(menuId, action) {
    fetch("{{ route('checkout.update_cart') }}", {
        method: "POST", 
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ id: menuId, action: action })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('qty-' + menuId).textContent = data.newQty;
            document.getElementById('subtotal-' + menuId).textContent = 'Rp ' + data.itemSubtotal;
            document.getElementById('grand-total-display').textContent = 'Rp ' + data.grandTotal;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: data.message,
                confirmButtonColor: '#E31837'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal update jumlah', 'error');
    });
}

function removeItem(menuId) {
    Swal.fire({
        title: 'Hapus Menu?', text: "Anda yakin ingin menghapus menu ini?", icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#E31837', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('checkout.remove_item') }}", {
                method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id: menuId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.isEmpty) location.reload(); 
                    else {
                        document.getElementById('cart-row-' + menuId).remove();
                        document.getElementById('grand-total-display').textContent = 'Rp ' + data.grandTotal;
                    }
                }
            });
        }
    })
}
</script>
@endsection