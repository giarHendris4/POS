<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 15, 2);
            $table->decimal('price_yearly', 15, 2);
            $table->integer('max_users')->default(1);
            $table->integer('max_products')->default(100);
            $table->integer('max_transactions_per_month')->default(1000);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['slug', 'is_active']);
        });
        
        // Seed subscription plans
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for small businesses',
                'price_monthly' => 9.99,
                'price_yearly' => 99.99,
                'max_users' => 1,
                'max_products' => 100,
                'max_transactions_per_month' => 1000,
                'features' => json_encode(['POS System', 'Inventory Management', 'Basic Reports']),
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing businesses',
                'price_monthly' => 29.99,
                'price_yearly' => 299.99,
                'max_users' => 5,
                'max_products' => 1000,
                'max_transactions_per_month' => 10000,
                'features' => json_encode(['POS System', 'Inventory Management', 'Advanced Reports', 'Multi-location', 'API Access']),
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations',
                'price_monthly' => 99.99,
                'price_yearly' => 999.99,
                'max_users' => 999,
                'max_products' => 99999,
                'max_transactions_per_month' => 999999,
                'features' => json_encode(['All Pro Features', 'Custom Integrations', 'Priority Support', 'Dedicated Account Manager', 'White Label']),
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
