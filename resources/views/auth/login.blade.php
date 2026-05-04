@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border-t-4 border-brand-red relative overflow-hidden">
        
        {{-- Hiasan Background Abstrak --}}
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-50 z-0"></div>

        <div class="relative z-10 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Selamat Datang!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Masuk untuk mulai memesan wonton favoritmu.
            </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="mt-8 space-y-6 relative z-10" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="space-y-4">
                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-red focus:border-brand-red transition sm:text-sm" 
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password dengan Mata --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        
                        {{-- Input Password --}}
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-brand-red focus:border-brand-red transition sm:text-sm" 
                            placeholder="********">
                        
                        {{-- Tombol Mata --}}
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-brand-red focus:outline-none">
                            <i class="bi bi-eye-slash text-lg"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-brand-red hover:text-red-800 hover:underline transition">
                            Lupa password?
                        </a>
                    </div>
                @endif
            </div>

            <div>
                {{-- Tombol Merah (brand-red) --}}
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-brand-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red shadow-lg transform transition hover:-translate-y-1">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </span>
                    MASUK SEKARANG
                </button>
            </div>

            {{-- Link ke Register --}}
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-brand-red hover:text-red-800 hover:underline transition">
                        Daftar disini
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection