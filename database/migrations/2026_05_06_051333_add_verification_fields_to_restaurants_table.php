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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false);
            $table->timestamp('subscription_expires_at')->nullable();
            $table->enum('subscription_plan', ['monthly', 'semi_annual', 'annual'])->nullable();
            $table->decimal('subscription_amount', 8, 2)->nullable();
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'subscription_expires_at', 'subscription_plan', 'subscription_amount', 'verified_at']);
        });
    }
};
