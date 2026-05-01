<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->json('content_prompts')->nullable()->after('service_types');
            $table->json('service_labels')->nullable()->comment('Human-readable labels for service type slugs');
            $table->string('slug_prefix')->nullable()->after('service_types');
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['content_prompts', 'service_labels', 'slug_prefix']);
        });
    }
};
