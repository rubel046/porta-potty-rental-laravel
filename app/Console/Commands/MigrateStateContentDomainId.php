<?php

namespace App\Console\Commands;

use App\Models\State;
use Illuminate\Console\Command;

class MigrateStateContentDomainId extends Command
{
    protected $signature = 'migrate:state-content-domain {domain_id=1}';

    protected $description = 'Set domain_id for existing state page content data';

    public function handle(): int
    {
        $domainId = (int) $this->argument('domain_id');
        $this->info("Setting domain_id = {$domainId} for all state page content records...");

        $states = State::all();
        $count = 0;

        foreach ($states as $state) {
            if ($state->page_content && ! isset($state->page_content['domain_id'])) {
                $content = $state->page_content;
                $content['domain_id'] = $domainId;
                $state->page_content = $content;
                $state->save();
                $count++;
                $this->line("Updated: {$state->name}");
            }
        }

        $this->info("Done! Updated {$count} state records.");

        return Command::SUCCESS;
    }
}
