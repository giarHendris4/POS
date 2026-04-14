<?php

namespace Tests\Feature\Pos;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\CashFlow;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = $this->createTenant();
        $this->user = $this->createUser($this->tenant, ['role' => 'owner']);
    }

    /** @test */
    public function process_transaction_creates_transaction_and_updates_stock()
    {
        $this->actingAs($this->user);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 10,
        ]);
        
        $cartItem = Cart::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 15000,
        ]);
        
        $service = new TransactionService([$cartItem]);
        $transaction = $service->process([
            'payment_method' => 'cash',
            'payment_amount' => 30000,
        ]);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'grand_total' => 30000,
            'status' => 'completed',
        ]);
        
        $this->assertEquals(8, $product->fresh()->current_stock);
    }

    /** @test */
    public function process_transaction_creates_cash_flow_debit()
    {
        $this->actingAs($this->user);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 10,
        ]);
        
        $cartItem = Cart::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 15000,
        ]);
        
        $service = new TransactionService([$cartItem]);
        $transaction = $service->process([
            'payment_method' => 'cash',
            'payment_amount' => 15000,
        ]);
        
        $this->assertDatabaseHas('cash_flows', [
            'transaction_id' => $transaction->id,
            'type' => 'debit',
            'category' => 'sale',
            'amount' => 15000,
        ]);
    }

    /** @test */
    public function process_transaction_fails_if_stock_insufficient()
    {
        $this->actingAs($this->user);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 1,
        ]);
        
        $cartItem = Cart::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 15000,
        ]);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Stok produk Test Product tidak mencukupi');
        
        $service = new TransactionService([$cartItem]);
        $service->process([
            'payment_method' => 'cash',
            'payment_amount' => 75000,
        ]);
    }

    /** @test */
    public function invoice_number_is_generated_correctly()
    {
        $this->actingAs($this->user);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 10,
        ]);
        
        $cartItem = Cart::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 15000,
        ]);
        
        $service = new TransactionService([$cartItem]);
        $transaction = $service->process([
            'payment_method' => 'cash',
            'payment_amount' => 15000,
        ]);
        
        $expectedPrefix = 'INV-' . now()->format('Ymd') . '-';
        $this->assertStringStartsWith($expectedPrefix, $transaction->invoice_number);
    }
}
