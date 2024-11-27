<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Console\Commands;

use Illuminate\Console\Command;
use aliirfaan\CitronelExternalServiceGenerator\Services\ExternalListenerGeneratorService;

class ListenerGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citronel:external-service-generate:listener {listener_path} {config_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate listener for external service';

    /**
     * Execute the console command.
     */
    public function handle(ExternalListenerGeneratorService $externalListenerGeneratorService)
    {
        $listenerPath = $this->argument('listener_path');
        $configKey = $this->argument('config_key');

        $generationResponse = $externalListenerGeneratorService->generate($listenerPath, $configKey);

        if (!$generationResponse['success']) {
            $this->error($generationResponse['message']);
        } else {
            $this->info($generationResponse['message']);
        }
    }
}
