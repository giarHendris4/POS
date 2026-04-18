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
                    Selamat datang kembali!
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 text-black border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="contoh@email.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>Password
                        </label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-3 text-black border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="••••••••">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                            Lupa password?
                        </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target mt-6">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>

                    <!-- Register Link -->
                    <p class="text-center text-sm text-gray-600 mt-4">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">
                            Daftar sekarang
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
