<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // STATES - Optimize state lookups
        Schema::table('states', function (Blueprint $table) {
            $table->index('is_active', 'states_is_active_index');
        });

        // CITIES - Optimize city lookups by name, state, and priority sorting
        Schema::table('cities', function (Blueprint $table) {
            $table->index(['state_id', 'priority'], 'cities_state_priority_index');
            $table->index('name', 'cities_name_index');
        });

        // SERVICE_PAGES - Critical for SEO page lookups
        Schema::table('service_pages', function (Blueprint $table) {
            $table->index(['is_published', 'city_id'], 'service_pages_published_city_index');
            $table->index(['is_published', 'service_type'], 'service_pages_published_type_index');
            $table->index(['is_published', 'seo_score'], 'service_pages_published_seo_index');
            $table->index(['city_id', 'is_published', 'service_type'], 'service_pages_city_published_type_index');
        });

        // BLOG_POSTS - Optimize blog listing and category filtering
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['is_published', 'is_featured'], 'blog_posts_published_featured_index');
            $table->index(['is_published', 'published_at'], 'blog_posts_published_date_index');
            $table->index(['is_published', 'views'], 'blog_posts_published_views_index');
        });

        // BLOG_CATEGORIES - Optimize category lookups
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->index('is_active', 'blog_categories_active_index');
            $table->index(['is_active', 'sort_order'], 'blog_categories_active_sort_index');
        });

        // FAQs - Optimize FAQ lookups
        Schema::table('faqs', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'faqs_active_sort_index');
            $table->index(['city_id', 'is_active', 'sort_order'], 'faqs_city_active_sort_index');
        });

        // TESTIMONIALS - Optimize testimonial lookups
        Schema::table('testimonials', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured'], 'testimonials_active_featured_index');
            $table->index(['is_active', 'service_type'], 'testimonials_active_type_index');
            $table->index(['city_id', 'is_active'], 'testimonials_city_active_index');
        });

        // PHONE_NUMBERS - Optimize phone number routing
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->index(['buyer_id', 'is_active'], 'phone_numbers_buyer_active_index');
            $table->index(['status', 'is_active'], 'phone_numbers_status_active_index');
        });

        // CALL_LOGS - Critical for reporting and analytics
        Schema::table('call_logs', function (Blueprint $table) {
            $table->index(['is_billable', 'call_started_at'], 'call_logs_billable_date_index');
            $table->index(['buyer_id', 'call_started_at'], 'call_logs_buyer_date_index');
            $table->index(['city_id', 'call_started_at'], 'call_logs_city_date_index');
            $table->index(['status', 'call_started_at'], 'call_logs_status_date_index');
            $table->index(['is_qualified', 'is_billable'], 'call_logs_qualified_billable_index');
        });

        // INVOICES - Optimize invoice lookups
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['buyer_id', 'status'], 'invoices_buyer_status_index');
            $table->index(['status', 'due_date'], 'invoices_status_due_index');
        });

        // BUYERS - Optimize buyer lookups
        Schema::table('buyers', function (Blueprint $table) {
            $table->index('is_active', 'buyers_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->dropIndex('states_is_active_index');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex('cities_state_priority_index');
            $table->dropIndex('cities_name_index');
        });

        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropIndex('service_pages_published_city_index');
            $table->dropIndex('service_pages_published_type_index');
            $table->dropIndex('service_pages_published_seo_index');
            $table->dropIndex('service_pages_city_published_type_index');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_published_featured_index');
            $table->dropIndex('blog_posts_published_date_index');
            $table->dropIndex('blog_posts_published_views_index');
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropIndex('blog_categories_active_index');
            $table->dropIndex('blog_categories_active_sort_index');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('faqs_active_sort_index');
            $table->dropIndex('faqs_city_active_sort_index');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('testimonials_active_featured_index');
            $table->dropIndex('testimonials_active_type_index');
            $table->dropIndex('testimonials_city_active_index');
        });

        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->dropIndex('phone_numbers_buyer_active_index');
            $table->dropIndex('phone_numbers_status_active_index');
        });

        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropIndex('call_logs_billable_date_index');
            $table->dropIndex('call_logs_buyer_date_index');
            $table->dropIndex('call_logs_city_date_index');
            $table->dropIndex('call_logs_status_date_index');
            $table->dropIndex('call_logs_qualified_billable_index');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_buyer_status_index');
            $table->dropIndex('invoices_status_due_index');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->dropIndex('buyers_active_index');
        });
    }
};
