<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_quality_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained();
            $table->unsignedSmallInteger('score');
            $table->string('grade', 1);
            $table->unsignedSmallInteger('word_count')->default(0);
            $table->unsignedSmallInteger('faq_count')->default(0);
            $table->unsignedSmallInteger('testimonial_count')->default(0);
            $table->json('details');
            $table->timestamp('scored_at')->nullable();
            $table->timestamps();

            $table->unique('service_page_id');
            $table->index('domain_id');
            $table->index('score');
            $table->index('grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_quality_scores');
    }
};
