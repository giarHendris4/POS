<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8 safe-top safe-bottom">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
                    <i class="fas fa-store text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    POS<span class="text-blue-600">UMKM</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-600 mt-2">
                    Daftar akun baru untuk memulai
                </p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user text-gray-400 mr-1"></i>Nama Lengkap
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="Contoh: Budi Santoso">
                    </div>

                    <!-- Nama Warung -->
                    <div>
                        <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-store-alt text-gray-400 mr-1"></i>Nama Warung / Toko
                        </label>
                        <input id="tenant_name" type="text" name="tenant_name" value="{{ old('tenant_name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="Contoh: Warung Sejahtera">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone text-gray-400 mr-1"></i>Nomor Telepon
                        </label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="Contoh: 08123456789">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="contoh@email.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>Password
                        </label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-check-circle text-gray-400 mr-1"></i>Konfirmasi Password
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="Ulangi password">
                    </div>

                    <!-- Terms -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            Saya setuju dengan 
                            <a href="{{ route('terms.show') }}" target="_blank" class="text-blue-600 hover:underline">Syarat & Ketentuan</a>
                            dan
                            <a href="{{ route('policy.show') }}" target="_blank" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
                        </label>
                    </div>
                    @endif

                    <!-- Register Button -->
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target mt-6">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>

                    <!-- Login Link -->
                    <p class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">
                            Login di sini
                        </a>
                    </p>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-500 mt-6">
                &copy; {{ date('Y') }} POS UMKM. All rights reserved.
            </p>
        </div>
    </div>
</x-guest-layout>
