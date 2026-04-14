<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PrintedReceipt extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'transaction_id',
        'receipt_number',
        'printed_by',
        'printed_at',
        'receipt_data',
        'total_amount',
        'printer_type',
    ];

    protected $casts = [
        'receipt_data' => 'array',
        'printed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function printedBy()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($receipt) {
            if (!$receipt->receipt_number) {
                $receipt->receipt_number = 'RCP-' . date('Ymd') . '-' . str_pad(
                    static::where('tenant_id', $receipt->tenant_id)
                        ->whereDate('created_at', now())
                        ->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
            
            if (!$receipt->printed_at) {
                $receipt->printed_at = now();
            }
            
            if (!$receipt->printed_by && auth()->check()) {
                $receipt->printed_by = auth()->id();
            }
        });
    }
}
