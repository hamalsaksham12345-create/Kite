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
        Schema::table('orders', function (Blueprint $table) {
            // Billing fields
            $table->decimal('subtotal', 12, 2)->default(0)->after('total_price');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('tax_percentage', 5, 2)->default(0)->after('tax_amount');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_percentage');
            $table->string('discount_code')->nullable()->after('discount_amount');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount_code');
            $table->decimal('service_charge', 12, 2)->default(0)->after('discount_percentage');
            $table->decimal('service_charge_percentage', 5, 2)->default(0)->after('service_charge');
            
            // Payment fields
            $table->string('payment_method')->nullable()->after('service_charge_percentage');
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->string('transaction_id')->nullable()->unique()->after('paid_at');
            
            // Invoice fields
            $table->string('invoice_number')->nullable()->unique()->after('transaction_id');
            $table->timestamp('invoice_generated_at')->nullable()->after('invoice_number');
            
            // Customer info
            $table->string('customer_name')->nullable()->after('invoice_generated_at');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('notes')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'tax_amount',
                'tax_percentage',
                'discount_amount',
                'discount_code',
                'discount_percentage',
                'service_charge',
                'service_charge_percentage',
                'payment_method',
                'payment_status',
                'paid_at',
                'transaction_id',
                'invoice_number',
                'invoice_generated_at',
                'customer_name',
                'customer_phone',
                'notes',
            ]);
        });
    }
};
