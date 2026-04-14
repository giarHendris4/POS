<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Branch;
use App\Models\SubscriptionPlan;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected bool $seed = false;

    protected function createTenant(array $attributes = []): Tenant
    {
        return Tenant::create(array_merge([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-' . uniqid(),
            'is_active' => true,
            'trial_ends_at' => now()->addDays(14),
        ], $attributes));
    }

    protected function createUser(Tenant $tenant, array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User',
            'email' => 'test' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'role' => 'owner',
            'is_active' => true,
        ], $attributes));
    }

    protected function createSuperAdmin(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Super Admin',
            'email' => 'admin' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => null,
            'role' => 'super_admin',
            'is_active' => true,
        ], $attributes));
    }

    protected function createBranch(Tenant $tenant, array $attributes = []): Branch
    {
        return Branch::create(array_merge([
            'tenant_id' => $tenant->id,
            'name' => 'Main Branch',
            'code' => 'BR' . uniqid(),
            'is_active' => true,
        ], $attributes));
    }

    protected function createSubscriptionPlan(array $attributes = []): SubscriptionPlan
    {
        return SubscriptionPlan::create(array_merge([
            'name' => 'Basic',
            'slug' => 'basic-' . uniqid(),
            'price_monthly' => 49000,
            'price_yearly' => 490000,
            'max_products' => 500,
            'max_users' => 2,
            'is_active' => true,
            'sort_order' => 1,
        ], $attributes));
    }
}
