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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 300);
            $table->string('slug', 300)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->longText('content_html')->nullable();
            $table->string('featured_image', 500)->nullable();
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('focus_keyword', 200)->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->json('schema_markup')->nullable();
            $table->integer('word_count')->default(0);
            $table->integer('views')->default(0);
            $table->integer('reading_time')->default(0);    // minutes
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('is_published');
            $table->index('blog_category_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
