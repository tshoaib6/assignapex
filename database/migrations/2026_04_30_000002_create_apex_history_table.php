<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apex_history', function (Blueprint $table) {
            $table->id();
            $table->string('process_id');          // e.g. CST151
            $table->string('process_step_status')->nullable();
            $table->string('step_name')->nullable();
            $table->string('step_user')->nullable();
            $table->dateTime('step_start')->nullable();
            $table->dateTime('step_end')->nullable();
            $table->integer('step_duration_min')->nullable();
            $table->unsignedInteger('process_id_num')->nullable(); // numeric part
            $table->timestamps();

            $table->index('process_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apex_history');
    }
};
