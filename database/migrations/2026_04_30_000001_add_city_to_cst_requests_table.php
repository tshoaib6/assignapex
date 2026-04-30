<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cst_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('cst_requests', 'city')) {
                $table->string('city')->nullable()->after('region');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cst_requests', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};
