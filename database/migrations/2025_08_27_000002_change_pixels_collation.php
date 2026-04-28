<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the table collation to utf8mb4_unicode_ci to support Arabic characters
        DB::statement('ALTER TABLE pixels CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        // Specifically ensure the city and region columns are utf8mb4
        DB::statement('ALTER TABLE pixels MODIFY city VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
        DB::statement('ALTER TABLE pixels MODIFY region VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to latin1 if needed (though usually not recommended to revert charset upgrades)
        // DB::statement('ALTER TABLE pixels CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci');
    }
};
