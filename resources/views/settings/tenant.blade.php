@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-store text-blue-600 mr-2"></i>
                Pengaturan Warung
            </h1>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-store text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">{{ $currentTenant->name ?? 'Nama Warung' }}</h2>
                    <p class="text-sm text-gray-500">Bergabung sejak {{ $currentTenant->created_at->format('d M Y') ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Warung</label>
                    <input type="text" value="{{ $currentTenant->name ?? '' }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50" disabled>{{ $currentTenant->address ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" value="{{ $currentTenant->phone ?? '' }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50" disabled>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Fitur edit profil warung akan hadir di update berikutnya.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
