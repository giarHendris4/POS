<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class Scanner extends Component
{
    public $barcode = '';
    public $cartItems = [];
    public $subtotal = 0;
    public $totalItems = 0;
    public $paymentAmount = 0;
    public $changeAmount = 0;
    public $paymentMethod = 'cash';
    public $showPaymentModal = false;
    public $showQuickAddModal = false;
    public $newProductBarcode = '';
    public $newProductName = '';
    public $newProductCostPrice = 0;
    public $newProductSellingPrice = 0;

    protected $listeners = [
        'barcodeScanned' => 'addToCart',
        'productAdded' => 'refreshCart',
        'checkoutComplete' => 'resetCart',
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function addToCart($barcode)
    {
        $tenantId = Auth::user()->tenant_id;
        
        $product = Product::where('tenant_id', $tenantId)
            ->where('barcode', $barcode)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            $this->newProductBarcode = $barcode;
            $this->showQuickAddModal = true;
            return;
        }

        if ($product->current_stock <= 0) {
            session()->flash('error', 'Stok produk habis.');
            return;
        }

        $this->addProductToCart($product);
        $this->barcode = '';
    }

    protected function addProductToCart(Product $product)
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItem = Cart::where('tenant_id', $tenantId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            })
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->selling_price,
                'expires_at' => now()->addHours(24),
            ]);
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function loadCart()
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartQuery = Cart::with('product')
            ->where('tenant_id', $tenantId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            });

        $this->cartItems = $cartQuery->get();
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        
        $this->totalItems = $this->cartItems->sum('quantity');
    }

    public function updateQuantity($cartId, $quantity)
    {
        $cartItem = Cart::find($cartId);
        
        if (!$cartItem) {
            return;
        }

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $product = Product::find($cartItem->product_id);
            
            if ($quantity > $product->current_stock) {
                session()->flash('error', 'Stok tidak mencukupi.');
                return;
            }
            
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($cartId)
    {
        Cart::find($cartId)?->delete();
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();
        $sessionId = session()->getId();

        Cart::where('tenant_id', $tenantId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            })
            ->delete();

        $this->loadCart();
    }

    public function openPaymentModal()
    {
        if ($this->totalItems === 0) {
            session()->flash('error', 'Keranjang masih kosong.');
            return;
        }

        $this->paymentAmount = 0;
        $this->changeAmount = 0;
        $this->showPaymentModal = true;
    }

    public function updatedPaymentAmount()
    {
        $this->changeAmount = max(0, $this->paymentAmount - $this->subtotal);
    }

    public function processPayment()
    {
        if ($this->paymentAmount < $this->subtotal && $this->paymentMethod === 'cash') {
            session()->flash('error', 'Jumlah bayar kurang.');
            return;
        }

        $this->dispatch('process-checkout', [
            'payment_method' => $this->paymentMethod,
            'payment_amount' => $this->paymentAmount,
        ]);

        $this->showPaymentModal = false;
    }

    public function quickAddProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductCostPrice' => 'required|numeric|min:0',
            'newProductSellingPrice' => 'required|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $branchId = Auth::user()->branch_id;

        $product = Product::create([
            'tenant_id' => $tenantId,
            'barcode' => $this->newProductBarcode,
            'name' => $this->newProductName,
            'cost_price' => $this->newProductCostPrice,
            'selling_price' => $this->newProductSellingPrice,
            'current_stock' => 1,
            'is_active' => true,
        ]);

        $this->showQuickAddModal = false;
        $this->newProductName = '';
        $this->newProductCostPrice = 0;
        $this->newProductSellingPrice = 0;

        $this->addProductToCart($product);
    }

    public function resetCart()
    {
        $this->loadCart();
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
        $this->barcode = '';
    }

    public function refreshCart()
    {
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.pos.scanner')
            ->layout('layouts.app');
    }
}
