<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->string('h1_title')->nullable()->after('timezone');
            $table->string('meta_title')->nullable()->after('h1_title');
            $table->string('meta_description')->nullable()->after('meta_title');
            $table->longText('content')->nullable()->after('meta_description');
            $table->json('images')->nullable()->after('content');
            $table->unsignedInteger('word_count')->nullable()->after('images');
            $table->float('seo_score')->nullable()->after('word_count');
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn([
                'h1_title',
                'meta_title',
                'meta_description',
                'content',
                'images',
                'word_count',
                'seo_score',
            ]);
        });
    }
};
