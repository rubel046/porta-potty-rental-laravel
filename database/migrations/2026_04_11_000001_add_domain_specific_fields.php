<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('primary_keyword')->nullable()->after('business_name');
            $table->json('secondary_keywords')->nullable()->after('primary_keyword');
            $table->string('primary_service')->nullable()->after('secondary_keywords');
            $table->json('service_types')->nullable()->after('primary_service');
            $table->string('tagline')->nullable()->after('service_types');
            $table->string('cta_phone')->nullable()->after('tagline');
        });

        Schema::table('service_pages', function (Blueprint $table) {
            if (! Schema::hasColumn('service_pages', 'domain_id')) {
                $table->foreignId('domain_id')->nullable()->after('city_id')->constrained('domains')->nullOnDelete();
            }
        });

        Schema::table('states', function (Blueprint $table) {
            if (! Schema::hasColumn('states', 'domain_id')) {
                $table->foreignId('domain_id')->nullable()->after('state_id')->constrained('domains')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropForeign(['domain_id']);
            $table->dropColumn('domain_id');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropForeign(['domain_id']);
            $table->dropColumn('domain_id');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['business_name', 'primary_keyword', 'secondary_keywords', 'primary_service', 'service_types', 'tagline', 'cta_phone']);
        });
    }
};
