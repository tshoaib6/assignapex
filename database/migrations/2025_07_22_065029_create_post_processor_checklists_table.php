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
    Schema::create('post_processor_checklists', function (Blueprint $table) {
        $table->id();
        $table->string('section'); // Automation, Voice KPIs, etc.
        $table->string('parent_title')->nullable(); // e.g., "Call Setup Success Rate > 85%"
        $table->string('check_point');
        $table->enum('status', ['Yes', 'No', 'N/A'])->default('N/A');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_processor_checklists');
    }
};
