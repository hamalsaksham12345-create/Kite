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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->unique()->constrained()->onDelete('cascade');
            $table->string('plan_name'); // basic, professional, enterprise
            $table->decimal('plan_price', 10, 2);
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->enum('status', ['active', 'inactive', 'cancelled', 'expired'])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('max_users')->default(5);
            $table->integer('max_menu_items')->default(100);
            $table->integer('max_tables')->default(20);
            $table->boolean('has_analytics')->default(true);
            $table->boolean('has_delivery')->default(false);
            $table->boolean('has_loyalty_program')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
