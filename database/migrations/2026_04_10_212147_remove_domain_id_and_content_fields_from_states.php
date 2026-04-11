<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('domain_states')) {
            return;
        }

        try {
            Schema::table('states', function (Blueprint $table) {
                $columnsToRemove = [];

                if (Schema::hasColumn('states', 'domain_id')) {
                    $columnsToRemove[] = 'domain_id';
                }
                if (Schema::hasColumn('states', 'h1_title')) {
                    $columnsToRemove[] = 'h1_title';
                }
                if (Schema::hasColumn('states', 'meta_title')) {
                    $columnsToRemove[] = 'meta_title';
                }
                if (Schema::hasColumn('states', 'meta_description')) {
                    $columnsToRemove[] = 'meta_description';
                }
                if (Schema::hasColumn('states', 'content')) {
                    $columnsToRemove[] = 'content';
                }
                if (Schema::hasColumn('states', 'images')) {
                    $columnsToRemove[] = 'images';
                }
                if (Schema::hasColumn('states', 'word_count')) {
                    $columnsToRemove[] = 'word_count';
                }
                if (Schema::hasColumn('states', 'seo_score')) {
                    $columnsToRemove[] = 'seo_score';
                }

                if (! empty($columnsToRemove)) {
                    $table->dropColumn($columnsToRemove);
                }
            });
        } catch (Throwable $e) {
            // Skip if already migrated or in test environment
        }
    }

    public function down(): void
    {
        // No rollback - fields moved to domain_states
    }
};
