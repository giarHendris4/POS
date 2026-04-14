<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin bypass branch scope
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Owner bypass branch scope (can see all branches)
        if ($user->isOwner()) {
            return $next($request);
        }

        // Cashier must have branch_id
        if ($user->isCashier() && !$user->branch_id) {
            abort(403, 'Cashier must be assigned to a branch.');
        }

        // Share current branch to views
        if ($user->branch_id) {
            view()->share('currentBranch', $user->branch);
        }

        return $next($request);
    }
}
