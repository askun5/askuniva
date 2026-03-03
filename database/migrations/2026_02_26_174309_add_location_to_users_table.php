<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('zip_code', 10)->nullable()->after('grade');
            $table->string('city', 100)->nullable()->after('zip_code');
            $table->string('state', 10)->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['zip_code', 'city', 'state']);
        });
    }
};
