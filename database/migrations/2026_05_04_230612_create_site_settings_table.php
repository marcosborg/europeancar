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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('European Car Sales and Rentals');
            $table->string('slogan')->default('Drive Europe. Choose Excellence.');
            $table->string('primary_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('legal_company_name')->nullable();
            $table->json('social_links')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('meta_pixel_id')->nullable();
            $table->json('footer_text')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('complaints_book_url')->nullable();
            $table->string('ral_url')->nullable();
            $table->json('seo_defaults')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
