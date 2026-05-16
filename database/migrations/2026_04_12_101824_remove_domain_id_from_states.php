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
                try {
                    $table->dropForeign(['domain_id']);
                } catch (Throwable $e) {
                    try {
                        $table->dropForeign('states_domain_id_foreign');
                    } catch (Throwable $e2) {
                        // FK may not exist — ignore.
                    }
                }

                try {
                    $table->dropIndex('states_domain_id_index');
                } catch (Throwable $e) {
                    // Index may not exist on all installations — ignore.
                }

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
