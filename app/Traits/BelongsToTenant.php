<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $query->where($query->getModel()->getTable() . '.tenant_id', Auth::user()->tenant_id);
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->tenant_id && !$model->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });

        static::saving(function ($model) {
            if (Auth::check() && Auth::user()->tenant_id) {
                // Ensure data is only saved to user's tenant
                if ($model->tenant_id && $model->tenant_id !== Auth::user()->tenant_id) {
                    abort(403, 'Cannot modify data from another tenant.');
                }
            }
        });
    }

    public function scopeWithoutTenantScope($query)
    {
        return $query->withoutGlobalScope('tenant');
    }
}
