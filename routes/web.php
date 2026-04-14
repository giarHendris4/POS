<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Protected routes with tenant middleware
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'tenant',        // Check subscription active
    'tenant.scope',  // Set tenant context
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // POS Routes
    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos.index');
    
    // Products Routes
    Route::get('/products', function () {
        return view('products.index');
    })->name('products.index');
    
    // Reports Routes (owner only)
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/reports/cash-flow', function () {
            return view('reports.cash-flow');
        })->name('reports.cash-flow');
        
        Route::get('/reports/profit-loss', function () {
            return view('reports.profit-loss');
        })->name('reports.profit-loss');
    });
    
    // Settings Routes (owner only)
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/settings/tenant', function () {
            return view('settings.tenant');
        })->name('settings.tenant');
        
        Route::get('/settings/subscription', function () {
            return view('settings.subscription');
        })->name('settings.subscription');
        
        Route::get('/settings/users', function () {
            return view('settings.users');
        })->name('settings.users');
    });
});
