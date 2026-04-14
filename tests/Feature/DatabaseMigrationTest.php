<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test all required tables exist.
     */
    public function test_tables_exist(): void
    {
        $requiredTables = [
            'users',
            'tenants',
            'products',
            'product_categories',
            'stock_movements',
            'transactions',
            'transaction_items',
            'cash_flows',
            'carts',
            'subscription_plans',
            'sessions',
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table '$table' should exist"
            );
        }
    }

    /**
     * Test tenant_id column exists in core tables.
     */
    public function test_tenant_id_column_exists_in_core_tables(): void
    {
        $tablesWithTenantId = [
            'users',
            'products',
            'product_categories',
            'stock_movements',
            'transactions',
            'transaction_items',
            'cash_flows',
            'carts',
        ];

        foreach ($tablesWithTenantId as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'tenant_id'),
                "Table '$table' should have tenant_id column"
            );
        }
    }

    /**
     * Test subscription plans are seeded.
     */
    public function test_subscription_plans_seeded(): void
    {
        $plans = DB::table('subscription_plans')->get();
        
        $this->assertGreaterThan(0, $plans->count(), 'Subscription plans should be seeded');
        
        $expectedSlugs = ['basic', 'pro', 'enterprise'];
        $actualSlugs = $plans->pluck('slug')->toArray();
        
        foreach ($expectedSlugs as $slug) {
            $this->assertContains($slug, $actualSlugs, "Subscription plan '$slug' should exist");
        }
    }

    /**
     * Test sessions table structure.
     */
    public function test_sessions_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('sessions'), 'Sessions table should exist');
        
        $requiredColumns = ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'];
        
        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('sessions', $column),
                "Sessions table should have '$column' column"
            );
        }
    }

    /**
     * Test products table structure.
     */
    public function test_products_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('products'), 'Products table should exist');
        
        $requiredColumns = [
            'id', 'tenant_id', 'barcode', 'name', 'description',
            'cost_price', 'selling_price', 'current_stock', 'min_stock_alert',
            'category_id', 'unit', 'image_path', 'is_active', 'metadata'
        ];
        
        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('products', $column),
                "Products table should have '$column' column"
            );
        }
    }

    /**
     * Test transactions table structure.
     */
    public function test_transactions_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('transactions'), 'Transactions table should exist');
        
        $requiredColumns = [
            'id', 'tenant_id', 'invoice_number', 'user_id', 'customer_id',
            'subtotal', 'discount_amount', 'discount_percentage', 'tax_amount',
            'tax_percentage', 'grand_total', 'payment_amount', 'change_amount',
            'payment_method', 'payment_reference', 'status', 'notes',
            'metadata', 'transaction_date'
        ];
        
        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('transactions', $column),
                "Transactions table should have '$column' column"
            );
        }
    }
}
