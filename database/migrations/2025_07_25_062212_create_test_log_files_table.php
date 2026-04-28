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
       Schema::create('test_log_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->text('file_link')->nullable();
            $table->integer('file_quantity')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('test_log_files');
    }
};
