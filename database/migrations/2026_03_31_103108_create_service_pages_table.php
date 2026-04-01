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
        Schema::create('service_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('service_type', 50);  // general, construction, wedding, event, luxury
            $table->string('slug', 300)->unique();
            $table->string('h1_title', 250);
            $table->string('meta_title', 200);
            $table->string('meta_description', 500);
            $table->longText('content');
            $table->longText('content_html')->nullable();   // rendered HTML cache
            $table->string('phone_number', 20)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->json('schema_markup')->nullable();
            $table->integer('word_count')->default(0);
            $table->integer('views')->default(0);
            $table->integer('calls_generated')->default(0);
            $table->float('seo_score')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['city_id', 'service_type']);
            $table->index('is_published');
            $table->index('service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pages');
    }
};
