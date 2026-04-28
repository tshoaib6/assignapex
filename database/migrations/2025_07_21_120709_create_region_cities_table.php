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
        Schema::create('region_cities', function (Blueprint $table) {
            $table->id(); // Serial No (auto increment)
            $table->string('region');
            $table->string('area');
            $table->string('city_highway');
            $table->string('test_type')->nullable(); // Test Type (Outdoor)
            $table->decimal('lat', 10, 7)->nullable(); // Latitude
            $table->decimal('lon', 10, 7)->nullable(); // Longitude
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_cities');
    }
};
