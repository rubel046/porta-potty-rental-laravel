<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            if (! Schema::hasColumn('domains', 'business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('domains', 'primary_keyword')) {
                $table->string('primary_keyword')->nullable()->after('business_name');
            }
            if (! Schema::hasColumn('domains', 'secondary_keywords')) {
                $table->json('secondary_keywords')->nullable()->after('primary_keyword');
            }
            if (! Schema::hasColumn('domains', 'primary_service')) {
                $table->string('primary_service')->nullable()->after('secondary_keywords');
            }
            if (! Schema::hasColumn('domains', 'service_types')) {
                $table->json('service_types')->nullable()->after('primary_service');
            }
            if (! Schema::hasColumn('domains', 'tagline')) {
                $table->string('tagline')->nullable()->after('service_types');
            }
            if (! Schema::hasColumn('domains', 'cta_phone')) {
                $table->string('cta_phone')->nullable()->after('tagline');
            }
        });

        Schema::table('service_pages', function (Blueprint $table) {
            if (! Schema::hasColumn('service_pages', 'domain_id')) {
                $table->foreignId('domain_id')->nullable()->constrained('domains')->nullOnDelete();
            }
        });

        Schema::table('states', function (Blueprint $table) {
            // NOT adding domain_id to states - content is in domain_states table now
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
