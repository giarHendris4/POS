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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['debit', 'credit']);
            $table->enum('category', ['sale', 'purchase', 'expense', 'investment', 'refund', 'adjustment', 'initial_capital']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description');
            $table->string('reference_number')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('cash_flow_date')->useCurrent();
            $table->timestamps();
            
            $table->index(['tenant_id', 'cash_flow_date']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'category']);
            $table->index(['tenant_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
