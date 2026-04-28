<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pp_data_validation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cst_request_id');
            $table->enum('decision', ['accept', 'reject', 'review']);
            $table->longText('notes')->nullable();
            $table->json('docs')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

            // Shortened constraint name
            $table->foreign('cst_request_id', 'ppdv_cst_request_fk')
                  ->references('id')
                  ->on('cst_requests')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pp_data_validation', function (Blueprint $table) {
            $table->dropForeign('ppdv_cst_request_fk');
        });

        Schema::dropIfExists('pp_data_validation');
    }
};
