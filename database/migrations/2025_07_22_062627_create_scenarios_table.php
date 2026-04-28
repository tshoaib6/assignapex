<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('scenario_type'); // Benchmarking, Drive Test, etc.
            $table->string('scenario');
            $table->string('description')->nullable();
            $table->string('network')->nullable();
            $table->string('duration')->nullable();
            $table->string('pause')->nullable();
            $table->integer('number_of_devices')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
