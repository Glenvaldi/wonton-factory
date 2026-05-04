@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    {{-- Menggunakan style card yang sama dengan Login & Register --}}
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border-t-4 border-brand-red relative overflow-hidden">
        
        {{-- Hiasan Background Abstrak (Opsional) --}}
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-50 z-0"></div>

        <div class="relative z-10 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Lupa Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan alamat email Anda untuk menerima tautan reset password.
            </p>
        </div>

        {{-- Session Status (Pesan 'Tautan telah dikirim') --}}
        @if (session('status'))
            <div class="p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg text-sm" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-8 space-y-6 relative z-10" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="space-y-4">
                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required autofocus
                            value="{{ old('email') }}"
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-brand-red focus:border-brand-red transition sm:text-sm"
                            placeholder="nama@contoh.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-brand-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red shadow-lg transform transition hover:-translate-y-1">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="bi bi-envelope-open-fill"></i>
                    </span>
                    KIRIM TAUTAN RESET PASSWORD
                </button>
            </div>

            {{-- Link ke Login --}}
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Kembali ke halaman? 
                    <a href="{{ route('login') }}" class="font-bold text-brand-red hover:text-red-800 hover:underline transition">
                        Masuk
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection