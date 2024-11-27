<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Console\Commands;

use Illuminate\Console\Command;
use aliirfaan\CitronelExternalServiceGenerator\Services\ExternalLogGeneratorService;

class LogGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citronel:external-service-generate:log {--log_type=request} {model_path} {config_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration file for external service';

    /**
     * Execute the console command.
     */
    public function handle(ExternalLogGeneratorService $externalLogGeneratorService)
    {
        $configKey = $this->argument('config_key');
        $modelPath = $this->argument('model_path');
        $logType = $this->option('log_type');

        $generationResponse = $externalLogGeneratorService->generate($logType, $modelPath, $configKey);

        if (!$generationResponse['success']) {
            $this->error($generationResponse['message']);
        } else {
            $this->info($generationResponse['message']);
        }
    }
}
