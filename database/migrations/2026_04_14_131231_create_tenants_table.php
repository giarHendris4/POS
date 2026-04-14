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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subdomain')->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->foreignId('subscription_plan_id')->nullable();
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['slug', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
