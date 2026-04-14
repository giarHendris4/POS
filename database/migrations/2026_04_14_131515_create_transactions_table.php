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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('invoice_number', 50);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('payment_amount', 15, 2);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'credit_card']);
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'invoice_number'], 'transactions_tenant_invoice_unique');
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'transaction_date']);
            $table->index(['tenant_id', 'payment_method']);
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
