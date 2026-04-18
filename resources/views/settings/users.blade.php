@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3 flex justify-between items-center">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-users text-indigo-600 mr-2"></i>
                Manajemen User
            </h1>
            <button onclick="alert('Fitur tambah user akan hadir di update berikutnya')" 
                    class="p-2 bg-indigo-600 text-white rounded-lg tap-target">
                <i class="fas fa-user-plus"></i>
            </button>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="space-y-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Owner</span>
                </div>
            </div>
        </div>

        <div class="mt-8 p-6 bg-indigo-50 rounded-xl border border-indigo-200 text-center">
            <i class="fas fa-user-cog text-3xl text-indigo-600 mb-3"></i>
            <h3 class="font-semibold text-indigo-900 mb-2">Fitur Manajemen User</h3>
            <p class="text-sm text-indigo-700">
                Fitur lengkap manajemen user (tambah kasir, atur role, assign cabang) akan hadir di update berikutnya.
            </p>
        </div>
    </div>
</div>
@endsection
