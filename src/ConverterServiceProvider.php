<?php

namespace Aregsar\Converter;

use Illuminate\Support\ServiceProvider;

class ConverterServiceProvider extends ServiceProvider
{
    const CONVERTER_CONFIG_KEY = 'acme-converter';

    /**
     * Register the application services.
     */
    public function register()
    {
        //Add configuration loading code here
        $this->mergeConfigFrom(__DIR__ . '/../config/converter.php', self::CONVERTER_CONFIG_KEY);

        //Add Laravel container service registration code here
        $this->app->singleton('converter', function ($app) {
            return new Converter();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            //Add package resource publishing code here
            $this->publishes([
                __DIR__ . '/../config/converter.php' => config_path(self::CONVERTER_CONFIG_KEY . '.php'),
            ], 'config');

            //Add package commands here
            $this->commands([
                \Aregsar\Converter\Console\Commands\ConverterCommand::class,
            ]);
        }

        //add package resource loading path code here
        $this->loadRoutesFrom(__DIR__ . '/../routes/converter.php');
    }
}
