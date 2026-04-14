<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Cart;
use App\Models\CashFlow;
use App\Traits\GeneratesInvoiceNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    use GeneratesInvoiceNumber;

    protected array $cartItems;
    protected float $subtotal;
    protected float $discountAmount;
    protected float $discountPercentage;
    protected float $taxAmount;
    protected float $taxPercentage;
    protected float $grandTotal;

    public function __construct(array $cartItems)
    {
        $this->cartItems = $cartItems;
        $this->calculateTotals();
    }

    protected function calculateTotals(): void
    {
        $this->subtotal = collect($this->cartItems)->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $this->discountAmount = 0;
        $this->discountPercentage = 0;
        $this->taxAmount = 0;
        $this->taxPercentage = 0;
        $this->grandTotal = $this->subtotal - $this->discountAmount + $this->taxAmount;
    }

    public function process(array $paymentData): Transaction
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        $branchId = $user->branch_id;

        return DB::transaction(function () use ($tenantId, $branchId, $paymentData, $user) {
            $transaction = Transaction::create([
                'tenant_id' => $tenantId,
                'branch_id' => $branchId,
                'invoice_number' => $this->generateInvoiceNumber($tenantId),
                'user_id' => $user->id,
                'subtotal' => $this->subtotal,
                'discount_amount' => $this->discountAmount,
                'discount_percentage' => $this->discountPercentage,
                'tax_amount' => $this->taxAmount,
                'tax_percentage' => $this->taxPercentage,
                'grand_total' => $this->grandTotal,
                'payment_amount' => $paymentData['payment_amount'] ?? $this->grandTotal,
                'change_amount' => ($paymentData['payment_amount'] ?? $this->grandTotal) - $this->grandTotal,
                'payment_method' => $paymentData['payment_method'] ?? 'cash',
                'payment_reference' => $paymentData['payment_reference'] ?? null,
                'status' => 'completed',
                'transaction_date' => now(),
            ]);

            foreach ($this->cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                
                // VALIDASI STOK
                if ($product->current_stock < $cartItem->quantity) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}");
                }
                
                $costPriceSnapshot = $product->cost_price;
                $unitPriceSnapshot = $cartItem->unit_price;

                TransactionItem::create([
                    'tenant_id' => $tenantId,
                    'transaction_id' => $transaction->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product->name,
                    'product_barcode' => $product->barcode,
                    'quantity' => $cartItem->quantity,
                    'cost_price_snapshot' => $costPriceSnapshot,
                    'unit_price_snapshot' => $unitPriceSnapshot,
                    'subtotal' => $cartItem->quantity * $unitPriceSnapshot,
                ]);

                $stockBefore = $product->current_stock;
                $product->current_stock -= $cartItem->quantity;
                $product->save();

                StockMovement::create([
                    'tenant_id' => $tenantId,
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $cartItem->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $product->current_stock,
                    'reference_type' => 'transaction',
                    'reference_id' => $transaction->id,
                    'user_id' => $user->id,
                    'notes' => "Penjualan #{$transaction->invoice_number}",
                ]);
            }

            $this->clearCart($tenantId, $user->id, session()->getId());

            return $transaction;
        });
    }

    protected function clearCart(int $tenantId, int $userId, string $sessionId): void
    {
        Cart::where('tenant_id', $tenantId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            })
            ->delete();
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getGrandTotal(): float
    {
        return $this->grandTotal;
    }
}
