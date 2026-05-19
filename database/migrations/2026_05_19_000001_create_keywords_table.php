<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->unsignedInteger('volume')->nullable();
            $table->string('competition')->nullable()->comment('low, medium, high');
            $table->decimal('cpc', 8, 2)->nullable();
            $table->string('service_type')->nullable()->comment('maps to domain service_types');
            $table->unsignedTinyInteger('tier')->default(3)->comment('1=high priority, 2=medium, 3=long-tail');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['domain_id', 'keyword']);
            $table->index('service_type');
            $table->index('competition');
            $table->index('tier');
            $table->index('volume');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
