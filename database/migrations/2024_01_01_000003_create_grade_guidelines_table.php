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
        Schema::create('grade_guidelines', function (Blueprint $table) {
            $table->id();
            $table->enum('grade', ['grade_9_10', 'grade_11', 'grade_12'])->unique();
            $table->string('title');
            $table->longText('content'); // HTML content for rich text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_guidelines');
    }
};
