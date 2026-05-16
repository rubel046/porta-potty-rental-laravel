<?php

namespace App\Jobs;

use App\Models\ServicePage;
use App\Services\PageQualityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScoreServicePageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ServicePage $servicePage) {}

    public function handle(PageQualityService $service): void
    {
        $service->scoreAndPersist($this->servicePage);
    }
}
