<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gmb_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('gmb_account_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('blog_post'); // blog_post, manual, review_reply
            $table->text('content');
            $table->string('external_id')->nullable()->index();
            $table->foreignId('blog_post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending'); // pending, published, failed
            $table->text('response_data')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::table('gmb_accounts', function (Blueprint $table) {
            $table->timestamp('last_posted_at')->nullable()->after('last_synced_at');
            $table->integer('total_posts_count')->default(0)->after('last_posted_at');
            $table->timestamp('last_review_sync_at')->nullable()->after('total_posts_count');
            $table->integer('total_reviews_count')->default(0)->after('last_review_sync_at');
            $table->integer('unread_reviews_count')->default(0)->after('total_reviews_count');
            $table->text('last_review_reply_at')->nullable()->after('unread_reviews_count');
        });
    }

    public function down(): void
    {
        Schema::table('gmb_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'last_posted_at',
                'total_posts_count',
                'last_review_sync_at',
                'total_reviews_count',
                'unread_reviews_count',
                'last_review_reply_at',
            ]);
        });

        Schema::dropIfExists('gmb_posts');
    }
};
