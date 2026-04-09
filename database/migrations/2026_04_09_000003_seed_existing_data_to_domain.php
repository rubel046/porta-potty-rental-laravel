<?php

use App\Models\Domain;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $domain = Domain::where('domain', 'pottydirect.com')->first();

        if (! $domain) {
            $domain = Domain::create([
                'name' => 'Potty Direct',
                'domain' => 'pottydirect.com',
                'display_name' => 'Potty Direct',
                'primary_color' => '#22C55E',
                'is_active' => true,
            ]);
        }

        $tables = ['cities', 'buyers', 'phone_numbers', 'call_logs', 'invoices', 'blog_posts', 'blog_categories', 'states', 'service_pages', 'faqs', 'testimonials'];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'domain_id')) {
                DB::table($table)->whereNull('domain_id')->update(['domain_id' => $domain->id]);
            }
        }
    }

    public function down(): void {}
};
