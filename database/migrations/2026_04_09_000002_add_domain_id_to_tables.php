<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'cities',
            'buyers',
            'phone_numbers',
            'call_logs',
            'invoices',
            'blog_posts',
            'blog_categories',
            'states',
            'service_pages',
            'faqs',
            'testimonials',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('domain_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
                $table->index('domain_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'cities',
            'buyers',
            'phone_numbers',
            'call_logs',
            'invoices',
            'blog_posts',
            'blog_categories',
            'states',
            'service_pages',
            'faqs',
            'testimonials',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'domain_id')) {
                    $table->dropForeign(['domain_id']);
                    $table->dropColumn('domain_id');
                }
            });
        }
    }
};
