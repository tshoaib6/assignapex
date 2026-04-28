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
    Schema::create('reviewer_rejections', function (Blueprint $table) {
        $table->id();
        $table->enum('category', ['Field', 'Report']); // Two categories only
        $table->string('issue'); // e.g., route mismatched, missing slide
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer_rejections');
    }
};
