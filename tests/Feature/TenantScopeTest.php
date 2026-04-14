<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantScopeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function products_are_scoped_to_current_tenant()
    {
        $tenantA = $this->createTenant(['name' => 'Tenant A']);
        $tenantB = $this->createTenant(['name' => 'Tenant B']);

        $productA = Product::create([
            'tenant_id' => $tenantA->id,
            'barcode' => '123',
            'name' => 'Product A',
            'cost_price' => 10000,
            'selling_price' => 15000,
        ]);

        $productB = Product::create([
            'tenant_id' => $tenantB->id,
            'barcode' => '456',
            'name' => 'Product B',
            'cost_price' => 20000,
            'selling_price' => 25000,
        ]);

        $userA = $this->createUser($tenantA);

        $this->actingAs($userA);

        $products = Product::all();
        $this->assertCount(1, $products);
        $this->assertEquals('Product A', $products->first()->name);
    }

    /** @test */
    public function creating_product_auto_sets_tenant_id()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $this->actingAs($user);

        $product = Product::create([
            'barcode' => '789',
            'name' => 'Auto Tenant Product',
            'cost_price' => 30000,
            'selling_price' => 35000,
        ]);

        $this->assertEquals($tenant->id, $product->tenant_id);
    }

    /** @test */
    public function cannot_save_product_to_other_tenant()
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();
        $userA = $this->createUser($tenantA);

        $this->actingAs($userA);

        $this->expectException(\Exception::class);

        Product::create([
            'tenant_id' => $tenantB->id,
            'barcode' => '999',
            'name' => 'Wrong Tenant',
            'cost_price' => 10000,
            'selling_price' => 15000,
        ]);
    }

    /** @test */
    public function super_admin_can_see_all_tenant_data()
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        Product::create([
            'tenant_id' => $tenantA->id,
            'barcode' => '111',
            'name' => 'Product A',
            'cost_price' => 10000,
            'selling_price' => 15000,
        ]);

        Product::create([
            'tenant_id' => $tenantB->id,
            'barcode' => '222',
            'name' => 'Product B',
            'cost_price' => 20000,
            'selling_price' => 25000,
        ]);

        $superAdmin = $this->createSuperAdmin();
        $this->actingAs($superAdmin);

        $products = Product::withoutTenantScope()->get();
        $this->assertCount(2, $products);
    }
}
