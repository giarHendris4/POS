<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function owner_can_access_owner_only_routes()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/reports/cash-flow');

        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_cannot_access_owner_only_routes()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, ['role' => 'cashier']);

        $response = $this->actingAs($user)->get('/reports/cash-flow');

        $response->assertStatus(403);
    }

    /** @test */
    public function cashier_can_access_pos_route()
    {
        $tenant = $this->createTenant();
        $branch = $this->createBranch($tenant);
        $user = $this->createUser($tenant, [
            'role' => 'cashier',
            'branch_id' => $branch->id,
        ]);

        $response = $this->actingAs($user)->get('/pos');

        $response->assertStatus(200);
    }

    /** @test */
    public function super_admin_can_access_all_routes()
    {
        $user = $this->createSuperAdmin();

        $routes = [
            '/dashboard',
            '/pos',
            '/products',
            '/reports/cash-flow',
            '/settings/tenant',
            '/admin/tenants',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
        }
    }
}
