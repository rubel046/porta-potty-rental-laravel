<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Porta Potty Rental USA', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_phone', 'value' => '(888) 555-0199', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'default_phone_raw', 'value' => '+18885550199', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'business_email', 'value' => 'info@yourdomain.com', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'business_hours', 'value' => '24/7', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'call_min_duration', 'value' => '90', 'type' => 'integer', 'group' => 'calls'],
            ['key' => 'duplicate_hours', 'value' => '72', 'type' => 'integer', 'group' => 'calls'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
