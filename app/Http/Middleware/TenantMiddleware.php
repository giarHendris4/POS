<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->tenant_id) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak terasosiasi dengan tenant.',
            ]);
        }

        // Eager load tenant untuk mencegah N+1 query
        $user->load('tenant');
        $tenant = $user->tenant;

        if (!$tenant || !$tenant->isActive()) {
            if ($tenant && !$tenant->isActive()) {
                return redirect()->route('subscription.expired');
            }
            
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak terasosiasi dengan tenant.',
            ]);
        }

        // Share tenant data to all views
        view()->share('currentTenant', $tenant);

        return $next($request);
    }
}
