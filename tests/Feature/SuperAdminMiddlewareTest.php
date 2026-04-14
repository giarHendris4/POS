<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_can_access_admin_routes()
    {
        $user = $this->createSuperAdmin();

        $response = $this->actingAs($user)->get('/admin/tenants');

        $response->assertStatus(200);
    }

    /** @test */
    public function owner_cannot_access_admin_routes()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/admin/tenants');

        $response->assertStatus(403);
    }

    /** @test */
    public function cashier_cannot_access_admin_routes()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, ['role' => 'cashier']);

        $response = $this->actingAs($user)->get('/admin/tenants');

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_admin_routes()
    {
        $response = $this->get('/admin/tenants');

        $response->assertRedirect('/login');
    }
}
