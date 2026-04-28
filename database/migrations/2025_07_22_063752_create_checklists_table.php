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
       Schema::create('checklists', function (Blueprint $table) {
    $table->id();
    $table->string('section'); // Plan and Tools, Voice KPIs, etc.
    $table->string('check_point'); // e.g., Check Plan, Check Route
    $table->enum('status', ['Yes', 'No', 'N/A'])->default('N/A'); 
    $table->text('remarks')->nullable();
    $table->string('image')->nullable(); // for ODO picture
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
