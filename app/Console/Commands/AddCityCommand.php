<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\ServicePage;
use App\Models\State;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddCityCommand extends Command
{
    protected $signature = 'city:add {name : City name} {state : State code (e.g., TX)} {--area-code= : Area code} {--population= : Population number} {--generate-pages : Generate service pages for this city}';

    protected $description = 'Add a new city with optional service page generation';

    public function __construct(
        protected ContentGeneratorService $contentGenerator
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $stateCode = strtoupper($this->argument('state'));
        $areaCode = $this->option('area-code');
        $population = $this->option('population');
        $generatePages = $this->option('generate-pages');

        $state = State::where('code', $stateCode)->first();

        if (! $state) {
            $this->error("State '{$stateCode}' not found.");

            return Command::FAILURE;
        }

        $existingCity = City::where('name', $name)->where('state_id', $state->id)->first();

        if ($existingCity) {
            $this->error("City '{$name}' already exists in {$stateCode}.");

            return Command::FAILURE;
        }

        $city = City::create([
            'state_id' => $state->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'area_codes' => $areaCode ? [$areaCode] : [],
            'population' => $population ? (int) $population : null,
            'is_active' => true,
            'priority' => 50,
        ]);

        $this->info("Created city: {$name}, {$stateCode}");

        if ($generatePages) {
            $this->info('Generating service pages...');

            foreach (ServicePage::SERVICE_TYPES as $type => $label) {
                $pageData = $this->contentGenerator->generateServicePageContent($city, $type);

                ServicePage::create([
                    'city_id' => $city->id,
                    'service_type' => $type,
                    'slug' => $pageData['slug'],
                    'h1_title' => $pageData['h1_title'],
                    'meta_title' => $pageData['meta_title'],
                    'meta_description' => $pageData['meta_description'],
                    'content' => $pageData['content'],
                    'word_count' => $pageData['word_count'],
                    'is_published' => true,
                    'published_at' => now(),
                ]);

                $this->line("  - Created {$label} page");
            }

            $this->info('Generated '.count(ServicePage::SERVICE_TYPES)." service pages for {$name}.");
        }

        return Command::SUCCESS;
    }
}
