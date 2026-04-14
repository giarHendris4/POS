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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('barcode', 50);
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock_alert')->default(5);
            $table->foreignId('category_id')->nullable();
            $table->string('unit', 20)->default('pcs');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'barcode'], 'products_tenant_barcode_unique');
            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'name']);
            $table->index('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
