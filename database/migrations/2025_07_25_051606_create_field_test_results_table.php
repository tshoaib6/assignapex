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
        Schema::create('field_test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->unsignedBigInteger('driver_id');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('working_hours')->nullable(); // You can change to integer if you want numeric hours
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->json('docs')->nullable();
            // Foreign keys (optional, if these relationships exist)
            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_test_results');
    }
};