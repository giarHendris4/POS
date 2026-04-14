<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoftDeletesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deleted_user_is_soft_deleted()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertNotNull($user->fresh()->deleted_at);
    }

    /** @test */
    public function soft_deleted_user_not_included_in_default_queries()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $user->delete();

        $users = User::all();
        $this->assertCount(0, $users);

        $usersWithTrashed = User::withTrashed()->get();
        $this->assertCount(1, $usersWithTrashed);
    }

    /** @test */
    public function deleted_product_category_is_soft_deleted()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $this->actingAs($user);

        $category = ProductCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $category->delete();

        $this->assertSoftDeleted('product_categories', ['id' => $category->id]);
    }

    /** @test */
    public function deleted_branch_is_soft_deleted()
    {
        $tenant = $this->createTenant();
        $branch = $this->createBranch($tenant);

        $branch->delete();

        $this->assertSoftDeleted('branches', ['id' => $branch->id]);
    }
}
