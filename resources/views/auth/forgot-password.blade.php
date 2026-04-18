<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8 safe-top safe-bottom">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
                    <i class="fas fa-store text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Lupa Password</h1>
                <p class="text-sm text-gray-600 mt-2">
                    Masukkan email Anda untuk reset password
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 text-black border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none transition"
                               placeholder="contoh@email.com">
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
