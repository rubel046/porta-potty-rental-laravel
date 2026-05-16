<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('is_pillar')->default(false)->after('is_featured');
            $table->foreignId('pillar_id')->nullable()->after('is_pillar')
                ->constrained('blog_posts')->nullOnDelete();
            $table->index('is_pillar');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['pillar_id']);
            $table->dropIndex(['is_pillar']);
            $table->dropColumn(['is_pillar', 'pillar_id']);
        });
    }
};
