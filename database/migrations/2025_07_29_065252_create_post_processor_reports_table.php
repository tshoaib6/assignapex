<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostProcessorReportsTable extends Migration
{
    public function up()
    {
        Schema::create('post_processor_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cst_request_id')->constrained('cst_requests')->onDelete('cascade');
            $table->text('report_link');
            $table->longText('notes')->nullable();
            $table->json('docs')->nullable();
            $table->tinyInteger('status')->default(0); // 0 = pending, 1 = completed, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_processor_reports');
    }
}
