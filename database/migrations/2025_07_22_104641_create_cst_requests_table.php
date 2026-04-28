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
    Schema::create('cst_requests', function (Blueprint $table) {
       $table->id();
       $table->foreignId('user_id')->constrained()->onDelete('cascade');
       $table->string('unique_request_id')->unique();
       $table->string('request_type');
       $table->string('test_type');
       $table->string('region'); 
       $table->string('severity');
       $table->string('activity_type');
       $table->string('operator');
       $table->string('latitude')->nullable();
       $table->string('longitude')->nullable();
       $table->string('route_link')->nullable();
       $table->string('route_distance')->nullable();
       $table->text('route_details')->nullable();
       $table->string('scenario_type')->nullable();
       $table->string('scenario_set')->nullable(); 
       $table->text('test_details')->nullable();
       $table->string('kml_path')->nullable();
       $table->json('docs')->nullable();
       $table->string('step')->default(1);
       $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cst_requests');
    }
};
