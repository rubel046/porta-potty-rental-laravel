<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->enum('generation_status', ['pending', 'processing', 'success', 'failed'])->default('pending')->after('is_published');
            $table->text('generation_error')->nullable()->after('generation_status');
            $table->timestamp('generated_at')->nullable()->after('generation_error');
        });

        Schema::table('domain_states', function (Blueprint $table) {
            $table->enum('generation_status', ['pending', 'processing', 'success', 'failed'])->default('pending')->after('status');
            $table->text('generation_error')->nullable()->after('generation_status');
            $table->timestamp('generated_at')->nullable()->after('generation_error');
        });
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn(['generation_status', 'generation_error', 'generated_at']);
        });

        Schema::table('domain_states', function (Blueprint $table) {
            $table->dropColumn(['generation_status', 'generation_error', 'generated_at']);
        });
    }
};
