{{-- resources/views/partials/hero_slider.blade.php --}}

{{-- Pastikan Anda sudah mengimpor Swiper JS dan CSS di file layouts/customer.blade.php --}}

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @forelse($banners as $banner)
        
        {{-- Tampilkan hanya banner yang aktif --}}
        @if($banner->is_active)
        <a href="{{ $banner->link_url ?? '#' }}" class="swiper-slide block">
            
            {{-- Menggunakan struktur 1 kolom penuh untuk Single Image Banner --}}
            <div class="relative w-full overflow-hidden rounded-xl shadow-xl" style="height: 500px;">
                <img src="{{ asset('storage/' . $banner->image_path) }}" 
                    alt="{{ $banner->title }}" 
                    class="w-full h-full object-cover">
            </div>
            
        </a>
        @endif
        
        @empty
            <p class="p-10 text-center">Tidak ada promosi yang aktif saat ini. Tambahkan banner dari halaman Admin!</p>
        @endforelse
    </div>
    
    {{-- Elemen Paginasi Swiper --}}
    <div class="swiper-pagination mt-4"></div>
</div>

<script>
    // Inisialisasi Swiper (Wajib ada di sini jika Anda tidak memindahkannya ke layouts/customer.blade.php)
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ada data banner yang dikirim (lebih dari 1 banner diperlukan untuk efek loop)
        if (typeof Swiper !== 'undefined') {
            new Swiper(".mySwiper", {
                // Konfigurasi dasar slider
                loop: true,
                autoplay: {
                    delay: 5000, // 5 detik
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        }
    });
</script>