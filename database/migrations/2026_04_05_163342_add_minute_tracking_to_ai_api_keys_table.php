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
        Schema::table('ai_api_keys', function (Blueprint $table) {
            $table->unsignedInteger('requests_this_minute')->default(0)->after('requests_today');
            $table->timestamp('minute_reset_at')->nullable()->after('requests_this_minute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_api_keys', function (Blueprint $table) {
            $table->dropColumn(['requests_this_minute', 'minute_reset_at']);
        });
    }
};
