<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gmb_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('account_name');
            $table->string('account_email')->nullable();
            $table->string('location_id')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('auto_post')->default(false);
            $table->boolean('auto_reply_reviews')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gmb_accounts');
    }
};
