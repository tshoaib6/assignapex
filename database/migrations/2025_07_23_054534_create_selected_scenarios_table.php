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
        Schema::create('selected_scenarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->string('scenario');
            $table->text('description')->nullable();
            $table->text('network')->nullable();
            $table->string('duration')->nullable();
            $table->string('pause')->nullable();
            $table->text('devices')->nullable();
            $table->timestamps();

            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selected_scenarios');
    }
};
