<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['name' => 'Alabama', 'code' => 'AL', 'slug' => 'alabama', 'timezone' => 'America/Chicago'],
            ['name' => 'Alaska', 'code' => 'AK', 'slug' => 'alaska', 'timezone' => 'America/Anchorage'],
            ['name' => 'Arizona', 'code' => 'AZ', 'slug' => 'arizona', 'timezone' => 'America/Phoenix'],
            ['name' => 'Arkansas', 'code' => 'AR', 'slug' => 'arkansas', 'timezone' => 'America/Chicago'],
            ['name' => 'California', 'code' => 'CA', 'slug' => 'california', 'timezone' => 'America/Los_Angeles'],
            ['name' => 'Colorado', 'code' => 'CO', 'slug' => 'colorado', 'timezone' => 'America/Denver'],
            ['name' => 'Connecticut', 'code' => 'CT', 'slug' => 'connecticut', 'timezone' => 'America/New_York'],
            ['name' => 'Delaware', 'code' => 'DE', 'slug' => 'delaware', 'timezone' => 'America/New_York'],
            ['name' => 'Florida', 'code' => 'FL', 'slug' => 'florida', 'timezone' => 'America/New_York'],
            ['name' => 'Georgia', 'code' => 'GA', 'slug' => 'georgia', 'timezone' => 'America/New_York'],
            ['name' => 'Hawaii', 'code' => 'HI', 'slug' => 'hawaii', 'timezone' => 'Pacific/Honolulu'],
            ['name' => 'Idaho', 'code' => 'ID', 'slug' => 'idaho', 'timezone' => 'America/Boise'],
            ['name' => 'Illinois', 'code' => 'IL', 'slug' => 'illinois', 'timezone' => 'America/Chicago'],
            ['name' => 'Indiana', 'code' => 'IN', 'slug' => 'indiana', 'timezone' => 'America/Indiana/Indianapolis'],
            ['name' => 'Iowa', 'code' => 'IA', 'slug' => 'iowa', 'timezone' => 'America/Chicago'],
            ['name' => 'Kansas', 'code' => 'KS', 'slug' => 'kansas', 'timezone' => 'America/Chicago'],
            ['name' => 'Kentucky', 'code' => 'KY', 'slug' => 'kentucky', 'timezone' => 'America/Kentucky/Louisville'],
            ['name' => 'Louisiana', 'code' => 'LA', 'slug' => 'louisiana', 'timezone' => 'America/Chicago'],
            ['name' => 'Maine', 'code' => 'ME', 'slug' => 'maine', 'timezone' => 'America/New_York'],
            ['name' => 'Maryland', 'code' => 'MD', 'slug' => 'maryland', 'timezone' => 'America/New_York'],
            ['name' => 'Massachusetts', 'code' => 'MA', 'slug' => 'massachusetts', 'timezone' => 'America/New_York'],
            ['name' => 'Michigan', 'code' => 'MI', 'slug' => 'michigan', 'timezone' => 'America/Detroit'],
            ['name' => 'Minnesota', 'code' => 'MN', 'slug' => 'minnesota', 'timezone' => 'America/Chicago'],
            ['name' => 'Mississippi', 'code' => 'MS', 'slug' => 'mississippi', 'timezone' => 'America/Chicago'],
            ['name' => 'Missouri', 'code' => 'MO', 'slug' => 'missouri', 'timezone' => 'America/Chicago'],
            ['name' => 'Montana', 'code' => 'MT', 'slug' => 'montana', 'timezone' => 'America/Denver'],
            ['name' => 'Nebraska', 'code' => 'NE', 'slug' => 'nebraska', 'timezone' => 'America/Chicago'],
            ['name' => 'Nevada', 'code' => 'NV', 'slug' => 'nevada', 'timezone' => 'America/Los_Angeles'],
            ['name' => 'New Hampshire', 'code' => 'NH', 'slug' => 'new-hampshire', 'timezone' => 'America/New_York'],
            ['name' => 'New Jersey', 'code' => 'NJ', 'slug' => 'new-jersey', 'timezone' => 'America/New_York'],
            ['name' => 'New Mexico', 'code' => 'NM', 'slug' => 'new-mexico', 'timezone' => 'America/Denver'],
            ['name' => 'New York', 'code' => 'NY', 'slug' => 'new-york', 'timezone' => 'America/New_York'],
            ['name' => 'North Carolina', 'code' => 'NC', 'slug' => 'north-carolina', 'timezone' => 'America/New_York'],
            ['name' => 'North Dakota', 'code' => 'ND', 'slug' => 'north-dakota', 'timezone' => 'America/Chicago'],
            ['name' => 'Ohio', 'code' => 'OH', 'slug' => 'ohio', 'timezone' => 'America/New_York'],
            ['name' => 'Oklahoma', 'code' => 'OK', 'slug' => 'oklahoma', 'timezone' => 'America/Chicago'],
            ['name' => 'Oregon', 'code' => 'OR', 'slug' => 'oregon', 'timezone' => 'America/Los_Angeles'],
            ['name' => 'Pennsylvania', 'code' => 'PA', 'slug' => 'pennsylvania', 'timezone' => 'America/New_York'],
            ['name' => 'Rhode Island', 'code' => 'RI', 'slug' => 'rhode-island', 'timezone' => 'America/New_York'],
            ['name' => 'South Carolina', 'code' => 'SC', 'slug' => 'south-carolina', 'timezone' => 'America/New_York'],
            ['name' => 'South Dakota', 'code' => 'SD', 'slug' => 'south-dakota', 'timezone' => 'America/Chicago'],
            ['name' => 'Tennessee', 'code' => 'TN', 'slug' => 'tennessee', 'timezone' => 'America/Chicago'],
            ['name' => 'Texas', 'code' => 'TX', 'slug' => 'texas', 'timezone' => 'America/Chicago'],
            ['name' => 'Utah', 'code' => 'UT', 'slug' => 'utah', 'timezone' => 'America/Denver'],
            ['name' => 'Vermont', 'code' => 'VT', 'slug' => 'vermont', 'timezone' => 'America/New_York'],
            ['name' => 'Virginia', 'code' => 'VA', 'slug' => 'virginia', 'timezone' => 'America/New_York'],
            ['name' => 'Washington', 'code' => 'WA', 'slug' => 'washington', 'timezone' => 'America/Los_Angeles'],
            ['name' => 'West Virginia', 'code' => 'WV', 'slug' => 'west-virginia', 'timezone' => 'America/New_York'],
            ['name' => 'Wisconsin', 'code' => 'WI', 'slug' => 'wisconsin', 'timezone' => 'America/Chicago'],
            ['name' => 'Wyoming', 'code' => 'WY', 'slug' => 'wyoming', 'timezone' => 'America/Denver'],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(['code' => $state['code']], $state);
        }

        $this->command->info('✅ Seeded '.count($states).' US states');
    }
}
