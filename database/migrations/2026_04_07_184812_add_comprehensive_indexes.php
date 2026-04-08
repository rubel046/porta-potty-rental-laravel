<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'states' => ['states_is_active_index' => ['is_active']],
            'cities' => [
                'cities_state_priority_index' => ['state_id', 'priority'],
                'cities_name_index' => ['name'],
            ],
            'service_pages' => [
                'service_pages_published_city_index' => ['is_published', 'city_id'],
                'service_pages_published_type_index' => ['is_published', 'service_type'],
                'service_pages_published_seo_index' => ['is_published', 'seo_score'],
                'service_pages_city_published_type_index' => ['city_id', 'is_published', 'service_type'],
            ],
            'blog_posts' => [
                'blog_posts_published_featured_index' => ['is_published', 'is_featured'],
                'blog_posts_published_date_index' => ['is_published', 'published_at'],
                'blog_posts_published_views_index' => ['is_published', 'views'],
            ],
            'blog_categories' => [
                'blog_categories_active_index' => ['is_active'],
                'blog_categories_active_sort_index' => ['is_active', 'sort_order'],
            ],
            'faqs' => [
                'faqs_active_sort_index' => ['is_active', 'sort_order'],
                'faqs_city_active_sort_index' => ['city_id', 'is_active', 'sort_order'],
            ],
            'testimonials' => [
                'testimonials_active_featured_index' => ['is_active', 'is_featured'],
                'testimonials_active_type_index' => ['is_active', 'service_type'],
                'testimonials_city_active_index' => ['city_id', 'is_active'],
            ],
            'phone_numbers' => [
                'phone_numbers_buyer_active_index' => ['buyer_id', 'is_active'],
                'phone_numbers_status_active_index' => ['status', 'is_active'],
            ],
            'call_logs' => [
                'call_logs_billable_date_index' => ['is_billable', 'call_started_at'],
                'call_logs_buyer_date_index' => ['buyer_id', 'call_started_at'],
                'call_logs_city_date_index' => ['city_id', 'call_started_at'],
                'call_logs_status_date_index' => ['status', 'call_started_at'],
                'call_logs_qualified_billable_index' => ['is_qualified', 'is_billable'],
            ],
            'invoices' => [
                'invoices_buyer_status_index' => ['buyer_id', 'status'],
                'invoices_status_due_index' => ['status', 'due_date'],
            ],
            'buyers' => ['buyers_active_index' => ['is_active']],
        ];

        foreach ($tables as $tableName => $indexes) {
            foreach ($indexes as $indexName => $columns) {
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
        $tables = [
            'states' => ['states_is_active_index'],
            'cities' => ['cities_state_priority_index', 'cities_name_index'],
            'service_pages' => ['service_pages_published_city_index', 'service_pages_published_type_index', 'service_pages_published_seo_index', 'service_pages_city_published_type_index'],
            'blog_posts' => ['blog_posts_published_featured_index', 'blog_posts_published_date_index', 'blog_posts_published_views_index'],
            'blog_categories' => ['blog_categories_active_index', 'blog_categories_active_sort_index'],
            'faqs' => ['faqs_active_sort_index', 'faqs_city_active_sort_index'],
            'testimonials' => ['testimonials_active_featured_index', 'testimonials_active_type_index', 'testimonials_city_active_index'],
            'phone_numbers' => ['phone_numbers_buyer_active_index', 'phone_numbers_status_active_index'],
            'call_logs' => ['call_logs_billable_date_index', 'call_logs_buyer_date_index', 'call_logs_city_date_index', 'call_logs_status_date_index', 'call_logs_qualified_billable_index'],
            'invoices' => ['invoices_buyer_status_index', 'invoices_status_due_index'],
            'buyers' => ['buyers_active_index'],
        ];

        foreach ($tables as $tableName => $indexes) {
            foreach ($indexes as $indexName) {
                if (! $this->indexExists($tableName, $indexName)) {
                    continue;
                }

                try {
                    DB::statement("ALTER TABLE {$tableName} DROP INDEX {$indexName}");
                } catch (Exception $e) {
                    // Index is referenced by a foreign key constraint - skip
                }
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$index}'"))->isNotEmpty();
    }
};
