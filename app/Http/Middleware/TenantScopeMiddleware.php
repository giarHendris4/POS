<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // This middleware sets the tenant context
        // The actual scoping is done via BelongsToTenant trait
        return $next($request);
    }
}
