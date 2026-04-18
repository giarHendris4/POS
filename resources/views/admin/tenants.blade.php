@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                Super Admin - Semua Tenant
            </h1>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
            <i class="fas fa-building text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Panel Super Admin</h3>
            <p class="text-gray-500">Fitur manajemen tenant dan user akan hadir di update berikutnya.</p>
        </div>
    </div>
</div>
@endsection
