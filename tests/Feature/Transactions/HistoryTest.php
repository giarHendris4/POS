<?php

namespace Tests\Feature\Transactions;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Transaction;
use Livewire\Livewire;
use App\Livewire\Transactions\History;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected $owner;
    protected $cashier;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = $this->createTenant();
        $this->owner = $this->createUser($this->tenant, ['role' => 'owner']);
        
        $branch = $this->createBranch($this->tenant);
        $this->cashier = $this->createUser($this->tenant, [
            'role' => 'cashier',
            'branch_id' => $branch->id,
        ]);
    }

    /** @test */
    public function owner_can_access_history_page()
    {
        $this->actingAs($this->owner);
        
        Livewire::test(History::class)
            ->assertSee('History Transaksi')
            ->assertSee('Total Omzet');
    }

    /** @test */
    public function cashier_cannot_access_history_page()
    {
        $this->actingAs($this->cashier);
        
        $this->get('/transactions/history')
            ->assertStatus(403);
    }

    /** @test */
    public function filter_by_date_range_shows_correct_transactions()
    {
        $this->actingAs($this->owner);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-001',
            'user_id' => $this->owner->id,
            'subtotal' => 10000,
            'grand_total' => 10000,
            'payment_amount' => 10000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now()->subDays(5),
        ]);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-002',
            'user_id' => $this->owner->id,
            'subtotal' => 20000,
            'grand_total' => 20000,
            'payment_amount' => 20000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);
        
        Livewire::test(History::class)
            ->set('filterType', 'range')
            ->set('startDate', now()->subDays(2)->format('Y-m-d'))
            ->set('endDate', now()->addDay()->format('Y-m-d'))
            ->assertSee('INV-002')
            ->assertDontSee('INV-001');
    }

    /** @test */
    public function filter_by_specific_date_shows_only_that_date()
    {
        $this->actingAs($this->owner);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-TODAY',
            'user_id' => $this->owner->id,
            'subtotal' => 10000,
            'grand_total' => 10000,
            'payment_amount' => 10000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-YESTERDAY',
            'user_id' => $this->owner->id,
            'subtotal' => 20000,
            'grand_total' => 20000,
            'payment_amount' => 20000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now()->subDay(),
        ]);
        
        Livewire::test(History::class)
            ->set('filterType', 'today')
            ->set('selectedDate', now()->format('Y-m-d'))
            ->assertSee('INV-TODAY')
            ->assertDontSee('INV-YESTERDAY');
    }

    /** @test */
    public function total_amount_calculation_is_correct()
    {
        $this->actingAs($this->owner);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-001',
            'user_id' => $this->owner->id,
            'subtotal' => 10000,
            'grand_total' => 10000,
            'payment_amount' => 10000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);
        
        Transaction::create([
            'tenant_id' => $this->tenant->id,
            'invoice_number' => 'INV-002',
            'user_id' => $this->owner->id,
            'subtotal' => 20000,
            'grand_total' => 20000,
            'payment_amount' => 20000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);
        
        Livewire::test(History::class)
            ->set('filterType', 'today')
            ->set('selectedDate', now()->format('Y-m-d'))
            ->assertSee('30.000');
    }
}
