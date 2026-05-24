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
        Schema::create('website_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            
            // Section Type: hero, about, menu_preview, testimonials, gallery, cta, contact
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->json('data')->nullable(); // Flexible data storage
            
            // Display Settings
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            
            $table->timestamps();
            
            $table->index(['restaurant_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_sections');
    }
};
