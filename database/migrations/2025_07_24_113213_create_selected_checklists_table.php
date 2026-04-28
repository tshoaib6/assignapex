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
       Schema::create('selected_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->json('checklist_id')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->string('start_od_pic')->nullable(); // path to image or base64
            $table->string('end_od_pic')->nullable();   // path to image or base64
            $table->integer('starting_km')->nullable();
            $table->integer('ending_km')->nullable();
            $table->integer('total_km')->nullable();
            $table->integer('total_cost')->nullable();
            $table->boolean('is_endactivity_odmeter')->default(false);
            $table->string('status')->default(0); // path to image or base64
            $table->json('docs')->nullable();
            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selected_checklists');
    }
};
