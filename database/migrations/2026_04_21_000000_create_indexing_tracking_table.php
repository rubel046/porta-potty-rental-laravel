<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indexing_urls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('type')->default('service'); // service, state, blog
            $table->string('reference_type')->nullable(); // ServicePage, DomainState, BlogPost
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('indexed')->default(false);
            $table->timestamp('indexed_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->string('status')->default('pending'); // pending, submitted, indexed, failed
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('indexed');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indexing_urls');
    }
};
