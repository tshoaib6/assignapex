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
        Schema::create('team_leader_evaluations', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->string('decision');
            $table->text('notes')->nullable();
            $table->json('docs')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_leader_evaluations');
    }
};
