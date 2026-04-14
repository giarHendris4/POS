<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchScopeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cashier_only_sees_own_branch_transactions()
    {
        $tenant = $this->createTenant();
        $branchA = $this->createBranch($tenant, ['name' => 'Branch A', 'code' => 'BRA']);
        $branchB = $this->createBranch($tenant, ['name' => 'Branch B', 'code' => 'BRB']);

        $cashier = $this->createUser($tenant, [
            'role' => 'cashier',
            'branch_id' => $branchA->id,
        ]);

        Transaction::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchA->id,
            'invoice_number' => 'INV-A-001',
            'subtotal' => 100000,
            'grand_total' => 100000,
            'payment_amount' => 100000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        Transaction::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchB->id,
            'invoice_number' => 'INV-B-001',
            'subtotal' => 200000,
            'grand_total' => 200000,
            'payment_amount' => 200000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        $this->actingAs($cashier);

        $transactions = Transaction::all();
        $this->assertCount(1, $transactions);
        $this->assertEquals('INV-A-001', $transactions->first()->invoice_number);
    }

    /** @test */
    public function owner_sees_all_branch_transactions_in_tenant()
    {
        $tenant = $this->createTenant();
        $branchA = $this->createBranch($tenant, ['name' => 'Branch A', 'code' => 'BRA']);
        $branchB = $this->createBranch($tenant, ['name' => 'Branch B', 'code' => 'BRB']);

        $owner = $this->createUser($tenant, ['role' => 'owner']);

        Transaction::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchA->id,
            'invoice_number' => 'INV-A-001',
            'subtotal' => 100000,
            'grand_total' => 100000,
            'payment_amount' => 100000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        Transaction::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchB->id,
            'invoice_number' => 'INV-B-001',
            'subtotal' => 200000,
            'grand_total' => 200000,
            'payment_amount' => 200000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        $this->actingAs($owner);

        $transactions = Transaction::all();
        $this->assertCount(2, $transactions);
    }

    /** @test */
    public function cashier_without_branch_cannot_access_routes()
    {
        $tenant = $this->createTenant();
        $cashier = $this->createUser($tenant, [
            'role' => 'cashier',
            'branch_id' => null,
        ]);

        $this->actingAs($cashier);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->get('/pos');
    }

    /** @test */
    public function creating_transaction_auto_sets_branch_id_for_cashier()
    {
        $tenant = $this->createTenant();
        $branch = $this->createBranch($tenant);
        $cashier = $this->createUser($tenant, [
            'role' => 'cashier',
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($cashier);

        $transaction = Transaction::create([
            'tenant_id' => $tenant->id,
            'invoice_number' => 'INV-001',
            'subtotal' => 50000,
            'grand_total' => 50000,
            'payment_amount' => 50000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        $this->assertEquals($branch->id, $transaction->branch_id);
    }
}
