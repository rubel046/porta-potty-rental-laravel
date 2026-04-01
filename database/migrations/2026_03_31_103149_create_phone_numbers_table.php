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
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique();           // "+17135551234"
            $table->string('friendly_name', 50)->nullable();  // "(713) 555-1234"
            $table->string('area_code', 10);
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 50)->default('signalwire'); // signalwire, twilio
            $table->string('provider_sid', 100)->nullable();
            $table->decimal('monthly_cost', 8, 4)->default(1.00);
            $table->string('status', 20)->default('active');  // active, inactive, released
            $table->integer('total_calls')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['city_id', 'is_active']);
            $table->index('area_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
