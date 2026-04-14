<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    protected static function bootBelongsToBranch()
    {
        static::addGlobalScope('branch', function ($query) {
            $user = Auth::user();
            
            if (!$user) {
                return;
            }

            // Super admin sees all
            if ($user->isSuperAdmin()) {
                return;
            }

            // Owner sees all within their tenant
            if ($user->isOwner()) {
                $query->where($query->getModel()->getTable() . '.tenant_id', $user->tenant_id);
                return;
            }

            // Cashier only sees their branch
            if ($user->isCashier() && $user->branch_id) {
                $query->where($query->getModel()->getTable() . '.branch_id', $user->branch_id);
            }
        });

        static::creating(function ($model) {
            $user = Auth::user();
            
            if (!$user) {
                return;
            }

            if (!$model->tenant_id) {
                $model->tenant_id = $user->tenant_id;
            }

            // Auto-set branch_id for cashier
            if ($user->isCashier() && $user->branch_id && !$model->branch_id) {
                $model->branch_id = $user->branch_id;
            }
        });
    }

    public function scopeWithoutBranchScope($query)
    {
        return $query->withoutGlobalScope('branch');
    }
}
