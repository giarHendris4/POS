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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('cashier')->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('photo_path');
            }
            
            $table->index(['tenant_id', 'email']);
            $table->index(['tenant_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'role', 'phone', 'photo_path', 'is_active']);
        });
    }
};
