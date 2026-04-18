<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_cities', function (Blueprint $table) {
            $table->boolean('content_generated')->default(false)->after('status');
            $table->timestamp('content_generated_at')->nullable()->after('content_generated');
        });
    }

    public function down(): void
    {
        Schema::table('domain_cities', function (Blueprint $table) {
            $table->dropColumn(['content_generated', 'content_generated_at']);
        });
    }
};
