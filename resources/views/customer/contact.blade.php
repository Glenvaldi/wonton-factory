@extends('layouts.app')

@section('content')

{{-- 1. HERO HEADER (GOOGLE MAPS INTERAKTIF) --}}
<div class="relative h-[400px] md:h-[500px] w-full overflow-hidden group z-0">
    {{-- Peta Google Maps --}}
    <div class="absolute inset-0 bg-gray-200">
        <iframe 
            width="100%" 
            height="100%" 
            frameborder="0" 
            scrolling="no" 
            marginheight="0" 
            marginwidth="0" 
            src="https://maps.google.com/maps?q=Jl.+Nanas+No+8,+Kaumrejo,+Kecamatan+Ngantang,+Kabupaten+Malang&t=&z=15&ie=UTF8&iwloc=&output=embed"
            style="filter: grayscale(0%) contrast(1.1) opacity(0.9);"
            class="w-full h-full grayscale hover:grayscale-0 transition duration-700"
            allowfullscreen>
        </iframe>
    </div>
    
    {{-- Overlay Gradient (Agar teks terbaca) --}}
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 via-transparent to-transparent pointer-events-none"></div>
    
    {{-- Judul Halaman --}}
    <div class="absolute bottom-0 left-0 w-full pb-12 md:pb-20 text-center z-10 pointer-events-none">
        <span class="inline-block py-1 px-4 rounded-full bg-brand-red text-white text-xs font-bold tracking-widest mb-4 uppercase shadow-lg animate-fade-in-up">
            Get In Touch
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-2 tracking-tight drop-shadow-lg animate-fade-in-up delay-100">
            Hubungi Kami
        </h1>
        <p class="text-gray-300 font-medium text-lg animate-fade-in-up delay-200">
            Kami siap mendengar masukan dan pertanyaan Anda.
        </p>
    </div>
</div>

{{-- 2. KONTEN UTAMA --}}
<div class="container mx-auto px-4 relative z-10 -mt-16 pb-20">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl mx-auto items-start">
        
        {{-- INFORMASI KONTAK (KIRI) --}}
        <div class="lg:col-span-1 space-y-6 animate-fade-in-up delay-300">
            
            {{-- Card: Lokasi --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-12 h-12 bg-red-50 text-brand-red rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-brand-red group-hover:text-white transition-colors shadow-sm">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Lokasi Outlet</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-3">Jl. Nanas No 8, Kaumrejo,<br>Kec. Ngantang, Kab. Malang</p>
                <a href="https://www.google.com/maps/search/?api=1&query=Jl.+Nanas+No+8,+Kaumrejo,+Kecamatan+Ngantang,+Kabupaten+Malang" target="_blank" class="text-brand-red font-bold text-xs flex items-center gap-2 hover:gap-3 transition-all">
                    Buka di Google Maps <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- Card: WhatsApp --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors shadow-sm">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">WhatsApp & Telpon</h3>
                <p class="text-gray-500 text-sm mb-3">Respon cepat untuk pemesanan.</p>
                <a href="https://wa.me/6281216145841" target="_blank" class="text-base font-mono font-bold text-gray-800 hover:text-brand-red transition block bg-gray-50 px-3 py-2 rounded-lg text-center group-hover:bg-green-50 group-hover:text-green-700">
                    0812-1614-5841
                </a>
                <p class="text-[10px] text-gray-400 mt-2 flex items-center justify-center gap-1">
                    <i class="bi bi-clock"></i> Senin - Minggu: 10.00 - 22.00
                </p>
            </div>

            {{-- Card: Email --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Email Support</h3>
                <p class="text-gray-500 text-sm mb-3">Kritik, saran, dan kerjasama.</p>
                <a href="mailto:wontonfactory@gmail.com" class="text-blue-600 text-sm font-bold hover:underline break-all block">
                    wontonfactory@gmail.com
                </a>
            </div>

        </div>

        {{-- FORMULIR PESAN (KANAN) --}}
        <div class="lg:col-span-2 animate-fade-in-up delay-500 h-full">
            <div class="bg-white rounded-[2.5rem] shadow-xl p-8 border border-gray-100 relative overflow-hidden h-full flex flex-col justify-center">
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-brand-red/5 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/2"></div>

                <div class="relative z-10">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Kirim Pesan</h2>
                    <p class="text-gray-500 text-sm mb-8">Isi formulir ini, pesan Anda akan otomatis terformat dan dikirim ke WhatsApp kami.</p>

                    <form onsubmit="sendToWhatsapp(event)" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="group">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Nama Lengkap</label>
                                <input type="text" id="contact-name" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 transition font-medium text-sm text-gray-800 placeholder-gray-400"
                                    placeholder="Nama Anda">
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Email (Opsional)</label>
                                <input type="email" id="contact-email"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 transition font-medium text-sm text-gray-800 placeholder-gray-400"
                                    placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Topik</label>
                            <select id="contact-subject" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 transition font-medium text-sm text-gray-800 cursor-pointer appearance-none">
                                <option value="Tanya Menu">Pertanyaan Menu</option>
                                <option value="Komplain">Komplain Pesanan</option>
                                <option value="Kerjasama">Kerjasama / Bisnis</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Pesan Anda</label>
                            <textarea id="contact-message" rows="4" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 transition font-medium text-sm text-gray-800 placeholder-gray-400 resize-none"
                                placeholder="Tuliskan pertanyaan atau masukan Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-brand-dark text-white font-bold py-3.5 rounded-xl shadow-glow hover:bg-brand-red hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 text-base group">
                            <span>Kirim Pesan ke WhatsApp</span>
                            <i class="bi bi-send-fill group-hover:translate-x-1 transition-transform text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function sendToWhatsapp(e) {
        e.preventDefault();
        
        const name = document.getElementById('contact-name').value;
        const email = document.getElementById('contact-email').value;
        const subject = document.getElementById('contact-subject').value;
        const message = document.getElementById('contact-message').value;
        
        const adminPhone = '6281216145841'; 
        
        let text = `Halo Admin WontonFactory,%0A%0A`;
        text += `Saya *${name}* ingin bertanya mengenai *${subject}*.%0A`;
        if(email) text += `(Email: ${email})%0A`;
        text += `---------------------------%0A`;
        text += `${message}`;

        window.open(`https://wa.me/${adminPhone}?text=${text}`, '_blank');
    }
</script>
@endsection