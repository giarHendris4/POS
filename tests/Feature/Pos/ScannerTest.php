<?php

namespace Tests\Feature\Pos;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Cart;
use Livewire\Livewire;
use App\Livewire\Pos\Scanner;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScannerTest extends TestCase
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
    public function scanner_component_can_be_rendered()
    {
        $this->actingAs($this->user);
        
        Livewire::test(Scanner::class)
            ->assertSee('Scan barcode di sini')
            ->assertSee('Keranjang kosong');
    }

    /** @test */
    public function add_product_to_cart_via_barcode()
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
        
        Livewire::test(Scanner::class)
            ->call('addToCart', '1234567890')
            ->assertSee('Test Product')
            ->assertSee('15000');
    }

    /** @test */
    public function cannot_add_product_with_insufficient_stock()
    {
        $this->actingAs($this->user);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 0,
        ]);
        
        Livewire::test(Scanner::class)
            ->call('addToCart', '1234567890')
            ->assertSee('Stok produk habis');
    }

    /** @test */
    public function update_cart_quantity()
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
        
        Livewire::test(Scanner::class)
            ->call('updateQuantity', $cartItem->id, 3)
            ->assertSet('totalItems', 3);
    }

    /** @test */
    public function remove_item_from_cart()
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
        
        Livewire::test(Scanner::class)
            ->call('removeItem', $cartItem->id)
            ->assertSee('Keranjang kosong');
    }

    /** @test */
    public function clear_cart_requires_confirmation()
    {
        $this->actingAs($this->user);
        
        Livewire::test(Scanner::class)
            ->call('clearCart')
            ->assertDispatched('confirm-clear-cart');
    }

    /** @test */
    public function empty_barcode_shows_error()
    {
        $this->actingAs($this->user);
        
        Livewire::test(Scanner::class)
            ->call('addToCart', '')
            ->assertSee('Barcode tidak boleh kosong');
    }

    /** @test */
    public function invalid_payment_amount_shows_error()
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
        
        Livewire::test(Scanner::class)
            ->call('addToCart', '1234567890')
            ->set('paymentAmount', 0)
            ->call('processPayment')
            ->assertSee('Jumlah bayar tidak valid');
    }
}
