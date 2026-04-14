<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CashFlow;

class Transaction extends Model
{
    use SoftDeletes, BelongsToTenant, BelongsToBranch;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'invoice_number',
        'user_id',
        'customer_id',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'tax_amount',
        'tax_percentage',
        'grand_total',
        'payment_amount',
        'change_amount',
        'payment_method',
        'payment_reference',
        'status',
        'notes',
        'metadata',
        'transaction_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'metadata' => 'array',
        'transaction_date' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            if ($transaction->status === 'completed') {
                $previousBalance = CashFlow::where('tenant_id', $transaction->tenant_id)
                    ->orderBy('id', 'desc')
                    ->value('balance_after') ?? 0;

                CashFlow::create([
                    'tenant_id' => $transaction->tenant_id,
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'type' => 'debit',
                    'category' => 'sale',
                    'amount' => $transaction->grand_total,
                    'balance_before' => $previousBalance,
                    'balance_after' => $previousBalance + $transaction->grand_total,
                    'description' => "Penjualan #{$transaction->invoice_number}",
                    'cash_flow_date' => $transaction->transaction_date,
                ]);
            }
        });
    }
}
