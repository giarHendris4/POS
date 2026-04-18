<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS UMKM') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Mobile-first base styles */
        body {
            font-family: 'Figtree', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Safe area for notched phones */
        .safe-top { padding-top: env(safe-area-inset-top); }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
        
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Touch-friendly tap targets */
        .tap-target {
            min-height: 44px;
            min-width: 44px;
        }
    </style>
</head>
<body class="h-full bg-gray-50 antialiased">
    <div class="min-h-full flex flex-col">
        <!-- Top Navigation - Mobile Friendly -->
        <nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30 safe-top">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14 sm:h-16">
                    <!-- Logo & Hamburger -->
                    <div class="flex items-center">
                        <button @click="open = !open" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none lg:hidden tap-target">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 ml-2 lg:ml-0">
                            POS<span class="text-gray-900">UMKM</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-1">
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </x-nav-link>
                        <x-nav-link href="{{ route('pos.index') }}" :active="request()->routeIs('pos.index')">
                            <i class="fas fa-cash-register mr-2"></i>POS
                        </x-nav-link>
                        <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                            <i class="fas fa-box mr-2"></i>Produk
                        </x-nav-link>
                        
                        @if(auth()->user()?->isOwner() || auth()->user()?->isSuperAdmin())
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-chart-bar mr-2"></i>Laporan
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 z-50">
                                <a href="{{ route('reports.cash-flow') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Cash Flow
                                </a>
                                <a href="{{ route('reports.profit-loss') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-chart-line mr-2"></i>Laba Rugi
                                </a>
                                <a href="{{ route('transactions.history') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-history mr-2"></i>History
                                </a>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>Pengaturan
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 z-50">
                                <a href="{{ route('settings.tenant') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-store mr-2"></i>Warung
                                </a>
                                <a href="{{ route('settings.branches') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-code-branch mr-2"></i>Cabang
                                </a>
                                <a href="{{ route('settings.users') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-users mr-2"></i>User
                                </a>
                                <a href="{{ route('settings.subscription') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-crown mr-2"></i>Langganan
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if(auth()->user()?->isSuperAdmin())
                        <x-nav-link href="{{ route('admin.tenants') }}" :active="request()->routeIs('admin.*')">
                            <i class="fas fa-shield-alt mr-2"></i>Admin
                        </x-nav-link>
                        @endif
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <span class="hidden sm:inline text-sm text-gray-600">
                            {{ auth()->user()?->tenant?->name ?? '' }}
                        </span>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center tap-target">
                                <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200" src="{{ Auth::user()?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name ?? 'User') }}" alt="{{ Auth::user()?->name }}">
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border py-2 z-50">
                                <div class="px-4 py-3 border-b">
                                    <div class="font-medium">{{ Auth::user()?->name }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ Auth::user()?->email }}</div>
                                    <div class="text-xs text-blue-600 mt-1">{{ ucfirst(Auth::user()?->role ?? 'User') }}</div>
                                </div>
                                
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <a href="{{ route('api-tokens.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-key mr-2"></i>API Tokens
                                </a>
                                @endif
                                
                                <div class="border-t my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="fixed inset-y-0 left-0 w-72 bg-white shadow-xl z-40 lg:hidden safe-top" style="display: none;">
                <div class="p-4 border-b flex items-center justify-between">
                    <span class="font-bold text-lg text-blue-600">POS UMKM</span>
                    <button @click="open = false" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 tap-target">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-2 overflow-y-auto h-full pb-20 safe-bottom">
                    <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Utama</div>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-home w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('pos.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target {{ request()->routeIs('pos.index') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-cash-register w-6"></i>
                        <span class="ml-3">POS / Kasir</span>
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target {{ request()->routeIs('products.index') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-box w-6"></i>
                        <span class="ml-3">Produk</span>
                    </a>
                    
                    @if(auth()->user()?->isOwner() || auth()->user()?->isSuperAdmin())
                    <div class="px-3 py-2 mt-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan</div>
                    
                    <a href="{{ route('reports.cash-flow') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-money-bill-wave w-6"></i>
                        <span class="ml-3">Cash Flow</span>
                    </a>
                    
                    <a href="{{ route('reports.profit-loss') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-chart-line w-6"></i>
                        <span class="ml-3">Laba Rugi</span>
                    </a>
                    
                    <a href="{{ route('transactions.history') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-history w-6"></i>
                        <span class="ml-3">History Transaksi</span>
                    </a>
                    
                    <div class="px-3 py-2 mt-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaturan</div>
                    
                    <a href="{{ route('settings.tenant') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-store w-6"></i>
                        <span class="ml-3">Warung</span>
                    </a>
                    
                    <a href="{{ route('settings.branches') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-code-branch w-6"></i>
                        <span class="ml-3">Cabang</span>
                    </a>
                    
                    <a href="{{ route('settings.users') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-users w-6"></i>
                        <span class="ml-3">User / Kasir</span>
                    </a>
                    
                    <a href="{{ route('settings.subscription') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-crown w-6"></i>
                        <span class="ml-3">Langganan</span>
                    </a>
                    @endif
                    
                    @if(auth()->user()?->isSuperAdmin())
                    <div class="px-3 py-2 mt-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Super Admin</div>
                    
                    <a href="{{ route('admin.tenants') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-building w-6"></i>
                        <span class="ml-3">Semua Tenant</span>
                    </a>
                    
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-100 tap-target">
                        <i class="fas fa-users-cog w-6"></i>
                        <span class="ml-3">Semua User</span>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Backdrop for mobile drawer -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" style="display: none;"></div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
        
        <!-- Bottom Navigation - Mobile Only -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 safe-bottom z-20">
            <div class="grid grid-cols-5 h-16">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 tap-target {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-home text-xl"></i>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="{{ route('pos.index') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 tap-target {{ request()->routeIs('pos.index') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-cash-register text-xl"></i>
                    <span class="text-xs mt-1">POS</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 tap-target {{ request()->routeIs('products.index') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-box text-xl"></i>
                    <span class="text-xs mt-1">Produk</span>
                </a>
                <a href="{{ route('transactions.history') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 tap-target {{ request()->routeIs('transactions.history') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-history text-xl"></i>
                    <span class="text-xs mt-1">History</span>
                </a>
                <a href="{{ route('settings.tenant') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 tap-target {{ request()->routeIs('settings.*') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-cog text-xl"></i>
                    <span class="text-xs mt-1">Setting</span>
                </a>
            </div>
        </nav>
        
        <!-- Padding for bottom nav on mobile -->
        <div class="h-16 lg:h-0"></div>
    </div>

    @stack('modals')
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global notification handler
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                Swal.fire({
                    icon: event.type || 'success',
                    title: event.title || 'Sukses',
                    text: event.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
