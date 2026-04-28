<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostProcessorReportValidationsTable extends Migration
{
    public function up()
    {
        Schema::create('post_processor_report_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cst_request_id')->constrained('cst_requests')->onDelete('cascade');
            $table->string('report_validation_decision')->nullable(); // 'accept', 'reject', 'review'
            $table->longText('report_validation_notes')->nullable();
            $table->json('docs')->nullable();
            $table->tinyInteger('status')->default(0); // e.g., 0 = pending, 1 = finalized
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_processor_report_validations');
    }
}
