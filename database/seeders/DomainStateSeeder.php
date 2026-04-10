<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\DomainState;
use App\Models\State;
use Illuminate\Database\Seeder;

class DomainStateSeeder extends Seeder
{
    public function run(): void
    {
        $domains = Domain::where('is_active', true)->get();
        $states = State::all();

        foreach ($domains as $domain) {
            $status = $domain->id === 1 ? false : true;
            foreach ($states as $state) {
                DomainState::firstOrCreate(
                    ['domain_id' => $domain->id, 'state_id' => $state->id],
                    ['status' => $status]
                );
            }
        }

        $this->command->info('✅ Seeded domain_states for '.count($domains).' domains and '.count($states).' states');
    }
}
