<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_api_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('tokens_used_today')->default(0)->after('cooldown_until');
            $table->timestamp('tokens_reset_at')->nullable()->after('tokens_used_today');
            $table->unsignedBigInteger('requests_today')->default(0)->after('tokens_reset_at');
            $table->timestamp('requests_reset_at')->nullable()->after('requests_today');
        });
    }

    public function down(): void
    {
        Schema::table('ai_api_keys', function (Blueprint $table) {
            $table->dropColumn(['tokens_used_today', 'tokens_reset_at', 'requests_today', 'requests_reset_at']);
        });
    }
};
