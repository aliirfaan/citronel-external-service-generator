<?php

namespace aliirfaan\CitronelExternalServiceGenerator;

use aliirfaan\CitronelExternalServiceGenerator\Console\Commands\ConfigurationGenerator;
use aliirfaan\CitronelExternalServiceGenerator\Console\Commands\ExternalServiceGenerator;
use aliirfaan\CitronelExternalServiceGenerator\Console\Commands\LogGenerator;
use aliirfaan\CitronelExternalServiceGenerator\Console\Commands\EventGenerator;
use aliirfaan\CitronelExternalServiceGenerator\Console\Commands\ListenerGenerator;

class CitronelExternalServiceGeneratorProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigurationGenerator::class,
                ExternalServiceGenerator::class,
                LogGenerator::class,
                EventGenerator::class,
                ListenerGenerator::class
            ]);
        }
    }
}
