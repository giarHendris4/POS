<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'transaction_id',
        'product_id',
        'product_name',
        'product_barcode',
        'quantity',
        'cost_price_snapshot',
        'unit_price_snapshot',
        'discount_amount',
        'subtotal',
        'metadata',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost_price_snapshot' => 'decimal:2',
        'unit_price_snapshot' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
