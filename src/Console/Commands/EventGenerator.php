<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Console\Commands;

use Illuminate\Console\Command;
use aliirfaan\CitronelExternalServiceGenerator\Services\ExternalEventGeneratorService;

class EventGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citronel:external-service-generate:event {event_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate event for external service';

    /**
     * Execute the console command.
     */
    public function handle(ExternalEventGeneratorService $externalEventGeneratorService)
    {
        $eventPath = $this->argument('event_path');

        $generationResponse = $externalEventGeneratorService->generate($eventPath);

        if (!$generationResponse['success']) {
            $this->error($generationResponse['message']);
        } else {
            $this->info($generationResponse['message']);
        }
    }
}
