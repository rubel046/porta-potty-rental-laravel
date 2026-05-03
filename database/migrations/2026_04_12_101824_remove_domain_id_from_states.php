<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table) {
            if (Schema::hasColumn('states', 'domain_id')) {
                // Drop index before the column — SQLite fails on column drops
                // when an index still references the column.
                try {
                    $table->dropIndex('states_domain_id_index');
                } catch (\Throwable $e) {
                    // Index may not exist on all installations — ignore.
                }

                try {
                    $table->dropForeign(['domain_id']);
                } catch (\Throwable $e) {
                    // Same — some databases auto-drop the FK with the column.
                }

                $table->dropColumn('domain_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->foreignId('domain_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
