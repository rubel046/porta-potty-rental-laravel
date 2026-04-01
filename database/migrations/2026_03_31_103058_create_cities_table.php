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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->string('county', 100)->nullable();
            $table->string('area_codes', 100)->nullable();       // "713,281,832"
            $table->integer('population')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('nearby_cities')->nullable();            // ["Katy","Sugar Land"]
            $table->json('zip_codes')->nullable();                // ["77001","77002"]
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->text('city_description')->nullable();         // শহর সম্পর্কে তথ্য
            $table->string('climate_info', 500)->nullable();      // আবহাওয়া তথ্য
            $table->text('local_events')->nullable();             // লোকাল ইভেন্ট তথ্য
            $table->string('construction_info', 500)->nullable(); // কনস্ট্রাকশন ইন্ডাস্ট্রি
            $table->integer('priority')->default(0);              // SEO priority
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['state_id', 'is_active']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
