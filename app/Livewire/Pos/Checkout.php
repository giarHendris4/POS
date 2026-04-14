<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Services\TransactionService;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $paymentReference = '';
    public $cartItems = [];
    public $subtotal = 0;
    public $grandTotal = 0;
    public $changeAmount = 0;

    protected $listeners = ['processCheckout' => 'handleCheckout'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();
        $sessionId = session()->getId();

        $this->cartItems = Cart::with('product')
            ->where('tenant_id', $tenantId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            })
            ->get();

        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $this->grandTotal = $this->subtotal;
    }

    public function handleCheckout($paymentData)
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('checkout-error', message: 'Keranjang kosong.');
            return;
        }

        try {
            $service = new TransactionService($this->cartItems);
            
            $transaction = $service->process([
                'payment_method' => $paymentData['payment_method'] ?? 'cash',
                'payment_amount' => $paymentData['payment_amount'] ?? $service->getGrandTotal(),
                'payment_reference' => $paymentData['payment_reference'] ?? null,
            ]);

            $this->dispatch('checkout-complete', [
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'grand_total' => $transaction->grand_total,
                'payment_amount' => $transaction->payment_amount,
                'change_amount' => $transaction->change_amount,
            ]);

            $this->cartItems = collect();
            $this->subtotal = 0;
            $this->grandTotal = 0;

        } catch (\Exception $e) {
            $this->dispatch('checkout-error', message: 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pos.checkout');
    }
}
