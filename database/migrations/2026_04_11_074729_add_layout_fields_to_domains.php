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
        Schema::table('domains', function (Blueprint $table) {
            if (! Schema::hasColumn('domains', 'layout')) {
                $table->string('layout')->default('default')->after('domain');
            }
            if (! Schema::hasColumn('domains', 'theme_color')) {
                $table->string('theme_color')->default('#22C55E')->after('layout');
            }
            if (! Schema::hasColumn('domains', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('theme_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            //
        });
    }
};
