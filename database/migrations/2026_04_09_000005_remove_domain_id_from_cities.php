<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['domain_id']);
            $table->dropIndex('cities_domain_id_index');
            $table->dropColumn('domain_id');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('domain_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
    }
};
