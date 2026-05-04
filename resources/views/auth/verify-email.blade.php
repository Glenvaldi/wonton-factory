@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border-t-4 border-brand-red relative overflow-hidden">
        
        {{-- Hiasan Background Abstrak (Opsional) --}}
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-50 z-0"></div>

        <div class="relative z-10 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Verifikasi Email
            </h2>
        </div>

        <div class="relative z-10 space-y-6">
            <div class="text-sm text-gray-600">
                Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkannya lagi.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 font-medium text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg">
                    Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-3/4">
                    @csrf
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-brand-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red shadow-lg transform transition hover:-translate-y-1">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bi bi-send-fill"></i>
                        </span>
                        KIRIM ULANG EMAIL
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-1/4">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 text-sm text-gray-600 font-bold hover:text-brand-red hover:underline transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection