<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->timestamp('indexed_at')->nullable()->after('generated_at');
            $table->boolean('indexing_requested')->default(false)->after('indexed_at');
        });

        Schema::table('domain_states', function (Blueprint $table) {
            $table->timestamp('indexed_at')->nullable()->after('generated_at');
            $table->boolean('indexing_requested')->default(false)->after('indexed_at');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->timestamp('indexed_at')->nullable()->after('published_at');
            $table->boolean('indexing_requested')->default(false)->after('indexed_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn(['indexed_at', 'indexing_requested']);
        });

        Schema::table('domain_states', function (Blueprint $table) {
            $table->dropColumn(['indexed_at', 'indexing_requested']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['indexed_at', 'indexing_requested']);
        });
    }
};
