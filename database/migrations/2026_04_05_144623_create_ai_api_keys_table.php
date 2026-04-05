<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_api_keys', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['groq', 'claude', 'gemini', 'openai'])->comment('AI provider: groq, claude, gemini, openai');
            $table->string('api_key');
            $table->string('model');
            $table->string('name')->nullable()->comment('Friendly name for identification');
            $table->unsignedInteger('priority')->default(100)->comment('Lower = higher priority');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('failure_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('cooldown_until')->nullable()->comment('Temporary block until this time');
            $table->timestamps();

            $table->index(['is_active', 'priority']);
            $table->index(['provider', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_api_keys');
    }
};
