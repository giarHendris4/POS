<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center py-4 px-4 safe-top safe-bottom">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-xl mb-3">
                    <i class="fas fa-store text-2xl text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">
                    POS<span class="text-blue-600">UMKM</span>
                </h1>
                <p class="text-sm text-gray-600 mt-1">Daftar akun baru</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg p-5">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="Budi Santoso">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Warung / Toko</label>
                        <input type="text" name="tenant_name" value="{{ old('tenant_name') }}" required
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="Warung Sejahtera">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="08123456789">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="contoh@email.com">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                               placeholder="Ulangi password">
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="flex items-start py-1">
                        <input type="checkbox" name="terms" id="terms" required class="w-4 h-4 text-blue-600 border-gray-300 rounded mt-0.5">
                        <label for="terms" class="ml-2 text-xs text-gray-600">
                            Saya setuju dengan 
                            <a href="{{ route('terms.show') }}" target="_blank" class="text-blue-600">Syarat & Ketentuan</a>
                            dan
                            <a href="{{ route('policy.show') }}" target="_blank" class="text-blue-600">Kebijakan Privasi</a>
                        </label>
                    </div>
                    @endif

                    <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        Daftar Sekarang
                    </button>

                    <p class="text-center text-xs text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium">Login di sini</a>
                    </p>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">
                &copy; {{ date('Y') }} POS UMKM
            </p>
        </div>
    </div>
</x-guest-layout>
