<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostProcessorFinalChecklistConfirmationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('post_processor_final_checklist_confirmations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->enum('checklist_confirmation', ['confirmed', 'not_confirmed']);
            $table->integer('actual_km');
            $table->string('actual_hours');
            $table->json('docs')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Shorter foreign key constraint name
            $table->foreign('cst_request_id', 'ppfcc_cst_request_fk')
                  ->references('id')
                  ->on('cst_requests')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('post_processor_final_checklist_confirmations', function (Blueprint $table) {
            $table->dropForeign('ppfcc_cst_request_fk');
        });

        Schema::dropIfExists('post_processor_final_checklist_confirmations');
    }
}
