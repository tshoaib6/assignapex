<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCstFinalAcceptancesTable extends Migration
{
    public function up()
    {
        Schema::create('cst_final_acceptances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->string('decision');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->json('docs')->nullable();
            $table->foreign('cst_request_id')->references('id')->on('cst_requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cst_final_acceptances');
    }
}

