<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_and_creates_tenant()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name' => 'Warung John',
            'phone' => '08123456789',
            'terms' => 'on',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('tenants', [
            'name' => 'Warung John',
            'is_active' => true,
        ]);

        $tenant = Tenant::where('name', 'Warung John')->first();
        $this->assertNotNull($tenant->trial_ends_at);
        $this->assertTrue($tenant->isOnTrial());

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'tenant_id' => $tenant->id,
            'role' => 'owner',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function registration_requires_tenant_name()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name' => '',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('tenant_name');
    }

    /** @test */
    public function registration_creates_tenant_with_14_days_trial()
    {
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name' => 'Toko Jane',
            'terms' => 'on',
        ]);

        $response->assertRedirect('/dashboard');

        $tenant = Tenant::where('name', 'Toko Jane')->first();
        $this->assertEquals(now()->addDays(14)->format('Y-m-d'), $tenant->trial_ends_at->format('Y-m-d'));
    }

    /** @test */
    public function tenant_slug_is_automatically_generated()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name' => 'My Awesome Store',
            'terms' => 'on',
        ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'My Awesome Store',
            'slug' => 'my-awesome-store',
        ]);
    }
}
