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
        Schema::table('restaurant_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_settings', 'service_charge_percentage')) {
                $table->decimal('service_charge_percentage', 5, 2)->default(0)->after('tax_percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_settings', 'service_charge_percentage')) {
                $table->dropColumn('service_charge_percentage');
            }
        });
    }
};
