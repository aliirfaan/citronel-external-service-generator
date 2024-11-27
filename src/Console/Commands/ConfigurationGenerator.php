<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Console\Commands;

use Illuminate\Console\Command;
use aliirfaan\CitronelExternalServiceGenerator\Services\ConfigurationGeneratorService;

class ConfigurationGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citronel:external-service-generate:config {external_service_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate configuration file for external service';

    /**
     * Execute the console command.
     */
    public function handle(ConfigurationGeneratorService $configurationGeneratorService)
    {
        $externalServiceName = $this->argument('external_service_name');

        $generationResponse = $configurationGeneratorService->generate($externalServiceName);

        if (!$generationResponse['success']) {
            $this->error($generationResponse['message']);
        } else {
            $this->info($generationResponse['message']);
        }
    }
}
