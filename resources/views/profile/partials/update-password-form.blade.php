<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-gray-700 mb-1">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-red focus:ring-brand-red text-gray-800" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-gray-700 mb-1">Password Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-red focus:ring-brand-red text-gray-800" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-red focus:ring-brand-red text-gray-800" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg font-bold text-sm hover:bg-gray-700 transition">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>