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
        Schema::create('tester_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->unsignedBigInteger('tester_id');
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0); 
            $table->timestamps();
            $table->json('docs')->nullable();
            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
            $table->foreign('tester_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tester_assignments');
    }
};
