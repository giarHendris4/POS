@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-crown text-yellow-500 mr-2"></i>
                Langganan
            </h1>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        @php
            $tenant = Auth::user()->tenant;
            $isTrial = $tenant && $tenant->isOnTrial();
            $isSubscribed = $tenant && $tenant->isSubscribed();
            $trialEndsAt = $tenant->trial_ends_at ?? null;
            $subscriptionEndsAt = $tenant->subscription_ends_at ?? null;
        @endphp

        <!-- Status Langganan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="font-semibold text-gray-900 mb-4">Status Langganan</h2>
            
            @if($isTrial)
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center">
                    <i class="fas fa-clock text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-blue-900">Masa Trial Aktif</p>
                        <p class="text-sm text-blue-700">Berakhir pada: {{ $trialEndsAt->format('d M Y') }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ $trialEndsAt->diffInDays(now()) }} hari tersisa</p>
                    </div>
                </div>
            </div>
            @elseif($isSubscribed)
            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl text-green-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-green-900">Langganan Aktif</p>
                        <p class="text-sm text-green-700">Berakhir pada: {{ $subscriptionEndsAt->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl text-red-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-red-900">Tidak Ada Langganan Aktif</p>
                        <p class="text-sm text-red-700">Silakan pilih paket di bawah ini</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Paket Langganan -->
        <h2 class="font-semibold text-gray-900 mb-4">Pilih Paket</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <!-- Basic -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900">Basic</h3>
                <p class="text-sm text-gray-500 mt-1">Untuk warung kecil</p>
                <p class="text-3xl font-bold text-gray-900 mt-4">Rp 49.000<span class="text-sm font-normal text-gray-500">/bln</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>500 Produk</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>2 User</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Scan Barcode</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Laporan Dasar</li>
                </ul>
                <button onclick="alert('Fitur pembayaran akan hadir di update berikutnya')" 
                        class="w-full mt-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 tap-target">
                    Pilih Paket
                </button>
            </div>

            <!-- Pro -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-blue-500 p-6 relative">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-4 py-1 rounded-full text-xs font-medium">POPULER</span>
                <h3 class="text-xl font-bold text-gray-900">Pro</h3>
                <p class="text-sm text-gray-500 mt-1">Untuk warung berkembang</p>
                <p class="text-3xl font-bold text-gray-900 mt-4">Rp 99.000<span class="text-sm font-normal text-gray-500">/bln</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>2.000 Produk</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>5 User</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Multi Kasir</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Laporan Lengkap</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Export Excel</li>
                </ul>
                <button onclick="alert('Fitur pembayaran akan hadir di update berikutnya')" 
                        class="w-full mt-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 tap-target">
                    Pilih Paket
                </button>
            </div>

            <!-- Enterprise -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900">Enterprise</h3>
                <p class="text-sm text-gray-500 mt-1">Untuk toko besar</p>
                <p class="text-3xl font-bold text-gray-900 mt-4">Rp 249.000<span class="text-sm font-normal text-gray-500">/bln</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Unlimited Produk</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Unlimited User</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Multi Cabang</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>API Access</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Priority Support</li>
                </ul>
                <button onclick="alert('Fitur pembayaran akan hadir di update berikutnya')" 
                        class="w-full mt-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 tap-target">
                    Pilih Paket
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
