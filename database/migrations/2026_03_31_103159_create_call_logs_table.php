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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid', 100)->nullable()->unique();
            $table->string('caller_number', 20);
            $table->string('called_number', 20);              // SignalWire number
            $table->string('forwarded_to', 20)->nullable();    // Buyer's number
            $table->foreignId('phone_number_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_page_id')->nullable()->constrained()->nullOnDelete();

            // Call Details
            $table->integer('duration_seconds')->default(0);
            $table->integer('ring_duration')->default(0);
            $table->string('status', 50)->default('initiated');
            // initiated, ringing, in-progress, completed, no-answer, busy, failed, canceled

            // Qualification
            $table->boolean('is_qualified')->default(false);
            $table->boolean('is_duplicate')->default(false);
            $table->boolean('is_billable')->default(false);
            $table->boolean('ivr_passed')->default(false);     // IVR তে 1 প্রেস করেছে
            $table->string('disqualification_reason', 100)->nullable();
            // too_short, duplicate, out_of_area, after_hours, robot, no_ivr

            // Financial
            $table->decimal('payout', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);        // SignalWire cost
            $table->decimal('profit', 10, 2)->default(0);

            // Caller Info
            $table->string('caller_city', 100)->nullable();
            $table->string('caller_state', 50)->nullable();
            $table->string('caller_zip', 10)->nullable();
            $table->string('caller_country', 5)->default('US');

            // Recording
            $table->string('recording_url', 500)->nullable();
            $table->integer('recording_duration')->default(0);

            // Source Tracking
            $table->string('traffic_source', 100)->nullable(); // organic, craigslist, facebook, direct
            $table->string('landing_page', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();

            // Buyer Feedback
            $table->string('buyer_disposition', 50)->nullable();
            // booked, not_interested, price_shopper, wrong_area, callback, voicemail
            $table->text('buyer_notes')->nullable();

            $table->timestamp('call_started_at')->nullable();
            $table->timestamp('call_answered_at')->nullable();
            $table->timestamp('call_ended_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('caller_number');
            $table->index('status');
            $table->index('is_qualified');
            $table->index('is_billable');
            $table->index('call_started_at');
            $table->index(['buyer_id', 'is_billable']);
            $table->index(['city_id', 'is_qualified']);
            $table->index('traffic_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
