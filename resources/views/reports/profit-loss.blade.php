@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Laporan Laba Rugi
            </h1>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
            <i class="fas fa-calculator text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Fitur Laporan Laba Rugi</h3>
            <p class="text-gray-500 mb-4">Laporan laba rugi lengkap dengan HPP dan margin akan hadir di update berikutnya.</p>
            <a href="{{ route('transactions.history') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg">
                Lihat History Transaksi
            </a>
        </div>
    </div>
</div>
@endsection
