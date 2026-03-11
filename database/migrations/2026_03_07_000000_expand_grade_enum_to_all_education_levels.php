<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY grade ENUM('grade_9_10','grade_11','grade_12','community_college','undergraduate','graduate') NOT NULL DEFAULT 'grade_9_10'");
            DB::statement("ALTER TABLE grade_guidelines MODIFY grade ENUM('grade_9_10','grade_11','grade_12','community_college','undergraduate','graduate') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY grade ENUM('grade_9_10','grade_11','grade_12') NOT NULL DEFAULT 'grade_9_10'");
            DB::statement("ALTER TABLE grade_guidelines MODIFY grade ENUM('grade_9_10','grade_11','grade_12') NOT NULL");
        }
    }
};
