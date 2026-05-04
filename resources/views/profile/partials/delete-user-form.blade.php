<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Hapus Akun') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold text-sm hover:bg-red-700 transition">
        {{ __('Hapus Akun') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white rounded-xl">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                {{ __('Setelah akun dihapus, semua data akan hilang permanen. Masukkan password Anda untuk konfirmasi.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" class="w-3/4 px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 text-gray-800 focus:ring-red-500 focus:border-red-500" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                    {{ __('Batal') }}
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition">
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>