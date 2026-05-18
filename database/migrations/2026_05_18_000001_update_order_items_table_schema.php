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
        Schema::table('order_items', function (Blueprint $table) {
            // Drop existing columns if they exist
            if (Schema::hasColumn('order_items', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('order_items', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('order_items', 'special_instructions')) {
                $table->dropColumn('special_instructions');
            }
            if (Schema::hasColumn('order_items', 'status')) {
                $table->dropColumn('status');
            }

            // Add the simplified columns
            $table->decimal('price', 8, 2)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Revert to original schema
            $table->dropColumn('price');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['pending', 'preparing', 'ready'])->default('pending');
        });
    }
};
