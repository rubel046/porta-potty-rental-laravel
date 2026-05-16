<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('local_landmarks')->nullable();
            $table->text('neighborhood_type')->nullable(); // residential, commercial, mixed-use, industrial
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();

            $table->index(['city_id', 'is_active']);
        });

        Schema::create('neighborhood_service_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('neighborhood_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('service_type');
            $table->string('slug')->unique();
            $table->string('h1_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_html')->nullable();
            $table->json('images')->nullable();
            $table->integer('word_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('generation_status')->nullable(); // pending, processing, success, failed
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['neighborhood_id', 'domain_id', 'service_type'], 'nsp_unique_type');
            $table->index(['domain_id', 'is_published']);
            $table->index(['slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('neighborhood_service_pages');
        Schema::dropIfExists('neighborhoods');
    }
};
