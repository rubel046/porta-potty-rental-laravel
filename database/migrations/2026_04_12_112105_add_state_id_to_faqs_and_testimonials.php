<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'state_id')) {
                $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            }
        });

        Schema::table('testimonials', function (Blueprint $table) {
            if (! Schema::hasColumn('testimonials', 'state_id')) {
                $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
        });
    }
};
