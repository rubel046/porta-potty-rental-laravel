<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 200);
            $table->string('contact_name', 100);
            $table->string('phone', 20);
            $table->string('backup_phone', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->decimal('payout_per_call', 10, 2)->default(10.00);
            $table->integer('daily_call_cap')->default(20);
            $table->integer('monthly_call_cap')->default(500);
            $table->integer('concurrent_call_limit')->default(3);
            $table->json('serving_states')->nullable();       // ["TX","OK"]
            $table->json('serving_cities')->nullable();       // [1,2,3] city IDs
            $table->json('business_hours')->nullable();       // {"start":"07:00","end":"20:00"}
            $table->string('timezone', 50)->default('America/Chicago');
            $table->integer('ring_timeout')->default(25);     // seconds
            $table->integer('priority')->default(1);          // 1=highest
            $table->decimal('total_billed', 12, 2)->default(0);
            $table->integer('total_calls')->default(0);
            $table->decimal('balance', 12, 2)->default(0);    // prepaid balance
            $table->string('payment_method', 50)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
