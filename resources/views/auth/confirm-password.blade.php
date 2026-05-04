@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border-t-4 border-brand-red relative overflow-hidden">
        
        {{-- Hiasan Background Abstrak --}}
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-50 z-0"></div>

        <div class="relative z-10 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Konfirmasi Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Ini adalah area aman. Mohon masukkan password Anda untuk melanjutkan.
            </p>
        </div>
        
        @if ($errors->any())
            <div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm" role="alert">
                Password yang Anda masukkan salah.
            </div>
        @endif

        <form class="mt-8 space-y-6 relative z-10" method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="space-y-4">
                {{-- Password dengan Mata --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Anda</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-brand-red focus:border-brand-red transition sm:text-sm" 
                            placeholder="Masukkan password Anda">
                        
                        {{-- Tombol Mata --}}
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-brand-red focus:outline-none">
                            <i class="bi bi-eye-slash text-lg"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-brand-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red shadow-lg transform transition hover:-translate-y-1">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                    KONFIRMASI
                </button>
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