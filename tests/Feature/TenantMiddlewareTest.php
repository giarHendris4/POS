<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function active_tenant_can_access_protected_routes()
    {
        $tenant = $this->createTenant(['is_active' => true]);
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function inactive_tenant_cannot_access_protected_routes()
    {
        $tenant = $this->createTenant([
            'is_active' => false,
            'trial_ends_at' => now()->subDays(1),
            'subscription_ends_at' => null,
        ]);
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function expired_trial_tenant_cannot_access_protected_routes()
    {
        $tenant = $this->createTenant([
            'is_active' => true,
            'trial_ends_at' => now()->subDays(1),
            'subscription_ends_at' => null,
        ]);
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function tenant_with_active_subscription_can_access_routes()
    {
        $tenant = $this->createTenant([
            'is_active' => true,
            'trial_ends_at' => null,
            'subscription_ends_at' => now()->addDays(30),
        ]);
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_without_tenant_cannot_access_protected_routes()
    {
        $user = User::create([
            'name' => 'No Tenant User',
            'email' => 'notenant@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => null,
            'role' => 'owner',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
