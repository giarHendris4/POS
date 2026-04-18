@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3 flex justify-between items-center">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-code-branch text-purple-600 mr-2"></i>
                Manajemen Cabang
            </h1>
            <button onclick="alert('Fitur tambah cabang akan hadir di update berikutnya')" 
                    class="p-2 bg-purple-600 text-white rounded-lg tap-target">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="space-y-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium text-gray-900">Cabang Utama</h3>
                        <p class="text-xs text-gray-500 mt-1">Kode: MAIN01</p>
                        <p class="text-sm text-gray-600 mt-2">Jl. Raya Utama No. 123</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                </div>
            </div>
        </div>

        <div class="mt-8 p-6 bg-purple-50 rounded-xl border border-purple-200 text-center">
            <i class="fas fa-store-alt text-3xl text-purple-600 mb-3"></i>
            <h3 class="font-semibold text-purple-900 mb-2">Fitur Manajemen Cabang</h3>
            <p class="text-sm text-purple-700">
                Fitur lengkap manajemen cabang (CRUD, assign kasir) akan hadir di update berikutnya.
            </p>
        </div>
    </div>
</div>
@endsection
