<?php

namespace Aregsar\Converter;

use Illuminate\Support\ServiceProvider;

class ConverterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            //Add resource publishing code here
            //Add package commands here
        }

        //add resource loading code here
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //Add Laravel container service registration code here
        $this->app->singleton('converter', function ($app) {
            return new Converter();
        });

        //Add configuration loading code here
    }
}
