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
        Schema::create('vehicle_feature_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_feature_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 2)->index();
            $table->string('name');
            $table->timestamps();

            $table->unique(['vehicle_feature_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_feature_translations');
    }
};
