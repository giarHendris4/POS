@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3 flex justify-between items-center">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-box text-green-600 mr-2"></i>
                    Manajemen Produk
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                    {{ $currentTenant->name ?? '' }}
                </p>
            </div>
            <button onclick="alert('Fitur tambah produk akan hadir di update berikutnya')" 
                    class="p-2 bg-green-600 text-white rounded-lg tap-target">
                <i class="fas fa-plus"></i>
                <span class="hidden sm:inline ml-2">Tambah</span>
            </button>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       placeholder="Cari produk berdasarkan nama atau barcode..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:border-green-500 outline-none">
            </div>
        </div>

        <!-- Category Filter - Horizontal Scroll -->
        <div class="mb-4 overflow-x-auto scrollbar-hide -mx-4 px-4">
            <div class="flex space-x-2 pb-2">
                <button class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium whitespace-nowrap">
                    Semua
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm whitespace-nowrap">
                    Makanan
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm whitespace-nowrap">
                    Minuman
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm whitespace-nowrap">
                    Sembako
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm whitespace-nowrap">
                    Lainnya
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            <!-- Product Card Placeholder -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                    <i class="fas fa-box text-4xl text-gray-400"></i>
                </div>
                <h3 class="font-medium text-gray-900 text-sm sm:text-base truncate">Indomie Goreng</h3>
                <p class="text-xs text-gray-500 font-mono mt-0.5">8991234567890</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm sm:text-base font-bold text-green-600">Rp 3.500</span>
                    <span class="text-xs text-gray-500">Stok: 100</span>
                </div>
            </div>
            
            <!-- Placeholder untuk menunjukkan grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                    <i class="fas fa-box text-4xl text-gray-400"></i>
                </div>
                <h3 class="font-medium text-gray-900 text-sm sm:text-base truncate">Aqua 600ml</h3>
                <p class="text-xs text-gray-500 font-mono mt-0.5">8999876543210</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm sm:text-base font-bold text-green-600">Rp 4.000</span>
                    <span class="text-xs text-gray-500">Stok: 50</span>
                </div>
            </div>
        </div>

        <!-- Info: Fitur lengkap coming soon -->
        <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-200 text-center">
            <i class="fas fa-truck text-3xl text-blue-600 mb-3"></i>
            <h3 class="font-semibold text-blue-900 mb-2">Fitur Manajemen Produk</h3>
            <p class="text-sm text-blue-700 mb-4">
                Fitur lengkap manajemen produk (CRUD, import CSV, kategorisasi) akan hadir di update berikutnya.
            </p>
            <p class="text-xs text-blue-500">
                Untuk saat ini, produk dapat ditambahkan melalui halaman POS saat scan barcode baru.
            </p>
        </div>
    </div>
</div>
@endsection
