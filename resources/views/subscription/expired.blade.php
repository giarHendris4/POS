<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">⏰</div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Langganan Telah Berakhir</h1>
            <p class="text-gray-600 mb-6">Maaf, masa langganan atau trial Anda telah berakhir. Silakan hubungi admin untuk memperpanjang langganan.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Kembali ke Login
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
