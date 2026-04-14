<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'transaction_id',
        'user_id',
        'type',
        'category',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_number',
        'metadata',
        'cash_flow_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'cash_flow_date' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
