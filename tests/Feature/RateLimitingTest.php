<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_is_rate_limited_to_5_attempts_per_minute()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, [
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function successful_login_resets_rate_limiter()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, [
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        for ($i = 0; $i < 4; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
