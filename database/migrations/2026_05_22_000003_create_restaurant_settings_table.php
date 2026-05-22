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
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->unique()->constrained()->onDelete('cascade');
            $table->string('currency')->default('NPR');
            $table->string('timezone')->default('Asia/Kathmandu');
            $table->boolean('enable_online_ordering')->default(true);
            $table->boolean('enable_table_reservations')->default(true);
            $table->boolean('enable_delivery')->default(false);
            $table->integer('order_preparation_time')->default(30); // in minutes
            $table->decimal('delivery_charge', 8, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->text('business_hours')->nullable(); // JSON format
            $table->text('contact_email')->nullable();
            $table->text('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
