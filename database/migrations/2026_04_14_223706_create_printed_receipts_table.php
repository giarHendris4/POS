<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printed_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->string('receipt_number', 50);
            $table->foreignId('printed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('printed_at')->useCurrent();
            $table->json('receipt_data');
            $table->decimal('total_amount', 15, 2);
            $table->string('printer_type', 20)->default('thermal');
            $table->timestamps();
            
            $table->unique(['tenant_id', 'receipt_number'], 'printed_receipts_tenant_receipt_unique');
            $table->index(['tenant_id', 'printed_at']);
            $table->index(['tenant_id', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printed_receipts');
    }
};
