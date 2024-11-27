<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Console\Commands;

use Illuminate\Console\Command;
use aliirfaan\CitronelExternalServiceGenerator\Services\ExternalServiceGeneratorService;

class ExternalServiceGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citronel:external-service-generate:service {service_path} {config_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service class for external service';

    /**
     * Execute the console command.
     */
    public function handle(ExternalServiceGeneratorService $externalServiceGeneratorService)
    {
        $servicePath = $this->argument('service_path');
        $configKey = $this->argument('config_key');

        $generationResponse = $externalServiceGeneratorService->generate($servicePath, $configKey);

        if (!$generationResponse['success']) {
            $this->error($generationResponse['message']);
        } else {
            $this->info($generationResponse['message']);
        }
    }
}
