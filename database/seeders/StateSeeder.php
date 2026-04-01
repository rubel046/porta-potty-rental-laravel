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
            ['name' => 'Arizona', 'code' => 'AZ', 'slug' => 'arizona', 'timezone' => 'America/Phoenix'],
            ['name' => 'Arkansas', 'code' => 'AR', 'slug' => 'arkansas', 'timezone' => 'America/Chicago'],
            ['name' => 'Colorado', 'code' => 'CO', 'slug' => 'colorado', 'timezone' => 'America/Denver'],
            ['name' => 'Florida', 'code' => 'FL', 'slug' => 'florida', 'timezone' => 'America/New_York'],
            ['name' => 'Georgia', 'code' => 'GA', 'slug' => 'georgia', 'timezone' => 'America/New_York'],
            ['name' => 'Indiana', 'code' => 'IN', 'slug' => 'indiana', 'timezone' => 'America/Indiana/Indianapolis'],
            ['name' => 'Kentucky', 'code' => 'KY', 'slug' => 'kentucky', 'timezone' => 'America/Kentucky/Louisville'],
            ['name' => 'Louisiana', 'code' => 'LA', 'slug' => 'louisiana', 'timezone' => 'America/Chicago'],
            ['name' => 'Nebraska', 'code' => 'NE', 'slug' => 'nebraska', 'timezone' => 'America/Chicago'],
            ['name' => 'North Carolina', 'code' => 'NC', 'slug' => 'north-carolina', 'timezone' => 'America/New_York'],
            ['name' => 'Ohio', 'code' => 'OH', 'slug' => 'ohio', 'timezone' => 'America/New_York'],
            ['name' => 'Oklahoma', 'code' => 'OK', 'slug' => 'oklahoma', 'timezone' => 'America/Chicago'],
            ['name' => 'South Carolina', 'code' => 'SC', 'slug' => 'south-carolina', 'timezone' => 'America/New_York'],
            ['name' => 'Tennessee', 'code' => 'TN', 'slug' => 'tennessee', 'timezone' => 'America/Chicago'],
            ['name' => 'Texas', 'code' => 'TX', 'slug' => 'texas', 'timezone' => 'America/Chicago'],
            ['name' => 'Virginia', 'code' => 'VA', 'slug' => 'virginia', 'timezone' => 'America/New_York'],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(['code' => $state['code']], $state);
        }
    }
}
