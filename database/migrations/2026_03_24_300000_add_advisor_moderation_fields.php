<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('advisor_warnings')->default(0)->after('last_login_at');
            $table->timestamp('advisor_suspended_at')->nullable()->after('advisor_warnings');
        });

        Schema::create('ai_chat_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('ai_chat_sessions')->onDelete('cascade');
            $table->text('message_content');
            $table->string('reason', 50); // 'off_topic' or 'disrespectful'
            $table->unsignedTinyInteger('warning_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_warnings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['advisor_warnings', 'advisor_suspended_at']);
        });
    }
};
