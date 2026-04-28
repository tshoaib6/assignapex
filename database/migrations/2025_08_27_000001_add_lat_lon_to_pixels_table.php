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
        Schema::table('pixels', function (Blueprint $table) {
            if (!Schema::hasColumn('pixels', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('city');
            }
            if (!Schema::hasColumn('pixels', 'lon')) {
                $table->decimal('lon', 10, 7)->nullable()->after('lat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pixels', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lon']);
        });
    }
};
