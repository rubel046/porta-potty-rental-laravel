<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            if (! Schema::hasColumn('domains', 'website_url')) {
                $table->string('website_url', 255)->nullable()->after('business_name');
            }

            if (! Schema::hasColumn('domains', 'secondary_color')) {
                $table->string('secondary_color', 20)->nullable()->after('primary_color');
            }

            if (! Schema::hasColumn('domains', 'twitter_handle')) {
                $table->string('twitter_handle', 50)->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            foreach (['website_url', 'secondary_color', 'twitter_handle'] as $col) {
                if (Schema::hasColumn('domains', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
