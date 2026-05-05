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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('car_model_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('status')->default('draft')->index();
            $table->string('type')->default('sale')->index();
            $table->string('version')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->date('first_registration_date')->nullable();
            $table->unsignedInteger('mileage')->nullable()->index();
            $table->string('origin_country')->nullable()->index();
            $table->string('current_location')->nullable();
            $table->decimal('sale_price', 12, 2)->nullable()->index();
            $table->decimal('old_price', 12, 2)->nullable();
            $table->boolean('price_on_request')->default(false);
            $table->boolean('financing_available')->default(false)->index();
            $table->decimal('estimated_monthly_payment', 10, 2)->nullable();
            $table->boolean('trade_in_available')->default(false);
            $table->boolean('vat_deductible')->default(false);
            $table->unsignedSmallInteger('warranty_months')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('vin')->nullable();
            $table->unsignedTinyInteger('doors')->nullable();
            $table->unsignedTinyInteger('seats')->nullable();
            $table->string('exterior_color')->nullable()->index();
            $table->string('interior_color')->nullable();
            $table->string('paint_type')->nullable();
            $table->string('body_type')->nullable()->index();
            $table->string('segment')->nullable();
            $table->string('fuel_type')->nullable()->index();
            $table->string('transmission')->nullable()->index();
            $table->string('drivetrain')->nullable();
            $table->unsignedInteger('engine_capacity')->nullable();
            $table->unsignedSmallInteger('power_hp')->nullable()->index();
            $table->unsignedSmallInteger('power_kw')->nullable();
            $table->unsignedSmallInteger('torque_nm')->nullable();
            $table->string('euro_standard')->nullable();
            $table->decimal('co2_emissions', 8, 2)->nullable();
            $table->decimal('combined_consumption', 5, 2)->nullable();
            $table->unsignedSmallInteger('electric_range')->nullable();
            $table->decimal('battery_capacity_kwh', 6, 2)->nullable();
            $table->string('charging_ac')->nullable();
            $table->string('charging_dc')->nullable();
            $table->boolean('maintenance_history')->default(false);
            $table->boolean('service_book')->default(false);
            $table->date('inspection_valid_until')->nullable();
            $table->unsignedTinyInteger('previous_owners')->nullable();
            $table->boolean('non_smoker')->default(false);
            $table->boolean('accident_free')->default(false);
            $table->text('internal_notes')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->boolean('premium')->default(false)->index();
            $table->unsignedInteger('featured_order')->default(0)->index();
            $table->decimal('daily_price', 10, 2)->nullable();
            $table->decimal('weekly_price', 10, 2)->nullable();
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->unsignedInteger('included_km_per_day')->nullable();
            $table->decimal('extra_km_price', 8, 2)->nullable();
            $table->unsignedTinyInteger('minimum_driver_age')->nullable();
            $table->string('fuel_policy')->nullable();
            $table->boolean('delivery_collection_available')->default(false);
            $table->string('rental_availability')->nullable()->index();
            $table->text('rental_conditions')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
