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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_barcode', 50);
            $table->integer('quantity');
            $table->decimal('cost_price_snapshot', 15, 2);
            $table->decimal('unit_price_snapshot', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'product_id']);
            $table->index(['tenant_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
