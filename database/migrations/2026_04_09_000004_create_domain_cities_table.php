<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->unique(['domain_id', 'city_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_cities');
    }
};
