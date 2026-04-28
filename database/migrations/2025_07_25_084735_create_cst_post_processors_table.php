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
            Schema::create('cst_post_processors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->json('checklist_ids')->nullable(); 
            $table->string('status')->default('0'); 
            $table->timestamps();
            $table->json('docs')->nullable();
            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cst_post_processors');
    }
};
