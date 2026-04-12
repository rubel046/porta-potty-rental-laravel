<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table) {
            if (Schema::hasColumn('states', 'domain_id')) {
                $table->dropForeign(['domain_id']);
                $table->dropColumn('domain_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->foreignId('domain_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
