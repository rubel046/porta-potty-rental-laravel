<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = [
            'cities' => [
                'cities_slug_index' => ['slug'],
            ],
            'service_pages' => [
                'service_pages_slug_index' => ['slug'],
                'service_pages_city_type_index' => ['city_id', 'service_type'],
            ],
            'states' => [
                'states_slug_index' => ['slug'],
            ],
            'faqs' => [
                'faqs_city_service_index' => ['city_id', 'service_type'],
            ],
            'testimonials' => [
                'testimonials_city_service_index' => ['city_id', 'service_type'],
            ],
        ];

        foreach ($indexes as $tableName => $tableIndexes) {
            foreach ($tableIndexes as $indexName => $columns) {
                if ($this->indexExists($tableName, $indexName)) {
                    continue;
                }
                Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName) {
                    $table->index($columns, $indexName);
                });
            }
        }
    }

    public function down(): void
    {
        $indexes = [
            'cities' => ['cities_slug_index'],
            'service_pages' => ['service_pages_slug_index', 'service_pages_city_type_index'],
            'states' => ['states_slug_index'],
            'faqs' => ['faqs_city_service_index'],
            'testimonials' => ['testimonials_city_service_index'],
        ];

        foreach ($indexes as $tableName => $tableIndexes) {
            foreach ($tableIndexes as $indexName) {
                if (! $this->indexExists($tableName, $indexName)) {
                    continue;
                }
                try {
                    DB::statement("ALTER TABLE {$tableName} DROP INDEX {$indexName}");
                } catch (Exception $e) {
                    // Index might be referenced by a foreign key or not exist
                }
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list({$table})");

            return collect($indexes)->contains('name', $index);
        }

        return collect(DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$index}'"))->isNotEmpty();
    }
};
