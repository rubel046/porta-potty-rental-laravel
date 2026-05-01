<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->string('h1_title', 250)->nullable()->change();
            $table->string('meta_title', 200)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->longText('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->string('h1_title', 250)->nullable(false)->change();
            $table->string('meta_title', 200)->nullable(false)->change();
            $table->string('meta_description', 500)->nullable(false)->change();
            $table->longText('content')->nullable(false)->change();
        });
    }
};
