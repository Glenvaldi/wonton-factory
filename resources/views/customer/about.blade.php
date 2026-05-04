@extends('layouts.app')

@section('content')

{{-- 1. HERO SECTION --}}
<div class="relative bg-gray-900 text-white py-20">
    <div class="absolute inset-0 overflow-hidden">
        {{-- Ganti URL ini dengan foto dapur/restoran asli jika ada --}}
        <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=1200&auto=format&fit=crop" 
             class="w-full h-full object-cover opacity-30" alt="Kitchen Background">
    </div>
    <div class="relative container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Cerita Rasa Wonton Factory</h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            Berawal dari kecintaan pada kuliner otentik, kami menghadirkan kehangatan dalam setiap lipatan wonton.
        </p>
    </div>
</div>

{{-- 2. STORY SECTION --}}
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <div class="w-full h-64 md:h-96 rounded-2xl overflow-hidden shadow-2xl transform -rotate-2 hover:rotate-0 transition duration-500">
                <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?w=800&auto=format&fit=crop" 
                     class="w-full h-full object-cover" alt="Dimsum Chef">
            </div>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-l-4 border-pizza-red pl-4">Lebih Dari Sekadar Dimsum</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Wonton Factory didirikan dengan satu misi sederhana: menyajikan hidangan Asian Comfort Food yang <strong>Halal, Higienis, dan Terjangkau</strong> untuk semua kalangan.
            </p>
            <p class="text-gray-600 mb-6 leading-relaxed">
                Kami percaya bahwa makanan enak tidak harus mahal. Menggunakan bahan-bahan pilihan yang segar setiap hari, tim dapur kami meracik bumbu rahasia yang membuat setiap gigitan Wonton dan Dimsum kami begitu nagih.
            </p>
            
            <div class="grid grid-cols-2 gap-4 mt-8">
                <div class="bg-red-50 p-4 rounded-lg text-center">
                    <i class="bi bi-shield-check text-3xl text-pizza-red mb-2"></i>
                    <h4 class="font-bold text-gray-800">100% Halal</h4>
                    <p class="text-xs text-gray-500">Bahan terjamin aman</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg text-center">
                    <i class="bi bi-heart-pulse text-3xl text-pizza-red mb-2"></i>
                    <h4 class="font-bold text-gray-800">Fresh Daily</h4>
                    <p class="text-xs text-gray-500">Dibuat setiap hari</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. LOKASI / MAPS --}}
<div class="bg-light-grey py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Kunjungi Outlet Kami</h2>
        
        <div class="bg-white p-4 rounded-xl shadow-lg max-w-4xl mx-auto">
            {{-- Embed Google Maps --}}
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.331285223038!2d106.8117!3d-6.2209!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTMnMTUuMiJTIDEwNsKwNDgnNDIuMSJF!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" 
                width="100%" height="400" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy">
            </iframe>
            <div class="mt-4 text-left p-4">
                <h4 class="font-bold text-xl"><i class="bi bi-geo-alt-fill text-pizza-red me-2"></i> Wonton Factory Pusat</h4>
                <p class="text-gray-600">Jl. Makanan Enak No. 1, Jakarta Selatan</p>
                <p class="text-gray-600">Buka Setiap Hari: 10.00 - 22.00 WIB</p>
            </div>
        </div>
    </div>
</div>

@endsection