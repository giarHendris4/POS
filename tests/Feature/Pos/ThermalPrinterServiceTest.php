<?php

namespace Tests\Feature\Pos;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\ThermalPrinterService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThermalPrinterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected $user;
    protected Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = $this->createTenant([
            'name' => 'Test Store',
            'address' => 'Test Address',
            'phone' => '08123456789',
        ]);
        
        $this->user = $this->createUser($this->tenant);
        
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'barcode' => '1234567890',
            'name' => 'Test Product',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'current_stock' => 10,
        ]);
        
        $this->transaction = Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-20260101-0001',
            'user_id' => $this->user->id,
            'subtotal' => 15000,
            'grand_total' => 15000,
            'payment_amount' => 20000,
            'change_amount' => 5000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);
        
        TransactionItem::create([
            'tenant_id' => $this->tenant->id,
            'transaction_id' => $this->transaction->id,
            'product_id' => $product->id,
            'product_name' => 'Test Product',
            'product_barcode' => '1234567890',
            'quantity' => 1,
            'cost_price_snapshot' => 10000,
            'unit_price_snapshot' => 15000,
            'subtotal' => 15000,
        ]);
    }

    /** @test */
    public function generate_receipt_returns_formatted_string()
    {
        $service = new ThermalPrinterService($this->transaction);
        $receipt = $service->generateReceipt();
        
        $this->assertStringContainsString('TEST STORE', $receipt);
        $this->assertStringContainsString('INV-20260101-0001', $receipt);
        $this->assertStringContainsString('Test Product', $receipt);
        $this->assertStringContainsString('15.000', $receipt);
        $this->assertStringContainsString('TERIMA KASIH', $receipt);
    }

    /** @test */
    public function save_printed_receipt_creates_record()
    {
        $this->actingAs($this->user);
        
        $service = new ThermalPrinterService($this->transaction);
        $receipt = $service->savePrintedReceipt();
        
        $this->assertDatabaseHas('printed_receipts', [
            'transaction_id' => $this->transaction->id,
            'tenant_id' => $this->tenant->id,
            'printed_by' => $this->user->id,
            'total_amount' => 15000,
        ]);
    }
}
