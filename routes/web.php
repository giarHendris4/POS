<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pos\Scanner;
use App\Livewire\Transactions\History;

Route::get('/', function () {
    return view('welcome');
});

// Protected routes with tenant middleware
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'tenant',
    'tenant.scope',
    'branch.scope',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/pos', Scanner::class)->name('pos.index');
    
    Route::get('/products', function () {
        return view('products.index');
    })->name('products.index');
    
    Route::middleware(['role:owner,super_admin'])->group(function () {
        Route::get('/reports/cash-flow', function () {
            return view('reports.cash-flow');
        })->name('reports.cash-flow');
        
        Route::get('/reports/profit-loss', function () {
            return view('reports.profit-loss');
        })->name('reports.profit-loss');
    });
    
    Route::middleware(['role:owner,super_admin'])->group(function () {
        Route::get('/settings/tenant', function () {
            return view('settings.tenant');
        })->name('settings.tenant');
        
        Route::get('/settings/subscription', function () {
            return view('settings.subscription');
        })->name('settings.subscription');
        
        Route::get('/settings/users', function () {
            return view('settings.users');
        })->name('settings.users');
        
        Route::get('/settings/branches', function () {
            return view('settings.branches');
        })->name('settings.branches');
        
        Route::get('/transactions/history', History::class)->name('transactions.history');
    });
});

// Super Admin Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'super.admin',
])->prefix('admin')->group(function () {
    Route::get('/tenants', function () {
        return view('admin.tenants');
    })->name('admin.tenants');
    
    Route::get('/users', function () {
        return view('admin.users');
    })->name('admin.users');
});
