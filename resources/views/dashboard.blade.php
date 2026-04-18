@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-6 lg:py-8">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                {{ Auth::user()->tenant->name ?? 'POS UMKM' }}
                @if(Auth::user()->branch)
                    - {{ Auth::user()->branch->name }}
                @endif
            </p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Today's Sales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Penjualan Hari Ini</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                            Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Today's Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Transaksi Hari Ini</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                            {{ $todayTransactions ?? 0 }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Stok Menipis</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                            {{ $lowStockCount ?? 0 }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Stok Habis</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                            {{ $outOfStockCount ?? 0 }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-4 gap-3 sm:gap-4 mb-6">
            <a href="{{ route('pos.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 text-center hover:border-blue-300 transition tap-target">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-cash-register text-blue-600 sm:text-xl"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-700">POS</span>
            </a>
            <a href="{{ route('products.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 text-center hover:border-green-300 transition tap-target">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-box text-green-600 sm:text-xl"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Produk</span>
            </a>
            <a href="{{ route('transactions.history') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 text-center hover:border-purple-300 transition tap-target">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-history text-purple-600 sm:text-xl"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-700">History</span>
            </a>
            <a href="{{ route('reports.cash-flow') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 text-center hover:border-orange-300 transition tap-target">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-chart-bar text-orange-600 sm:text-xl"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Laporan</span>
            </a>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Terakhir</h2>
                <a href="{{ route('transactions.history') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentTransactions ?? [] as $transaction)
                <div class="px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $transaction->invoice_number }}</p>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm sm:text-base">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                        <span class="text-xs px-2 py-1 rounded-full 
                            @if($transaction->payment_method === 'cash') bg-green-100 text-green-800
                            @elseif($transaction->payment_method === 'transfer') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst($transaction->payment_method) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-receipt text-3xl mb-2 opacity-50"></i>
                    <p class="text-sm">Belum ada transaksi hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
