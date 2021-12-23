<?php

namespace Aregsar\Converter;

use Illuminate\Support\ServiceProvider;

class ConverterServiceProvider extends ServiceProvider
{
    const CONVERTER_CONFIG_KEY = 'acme-converter';
    const CONVERTER_VIEWS_NAMESPACE = 'acme-converter';
    const CONVERTER_COMPONENT_CLASS_TAG_PREFIX = 'acmeconverter';
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

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/' . self::CONVERTER_VIEWS_NAMESPACE)
            ], "views");

            $this->publishes([
                __DIR__ . '/../src/View/Components/' => app_path('View/Components/' . ucfirst(self::CONVERTER_COMPONENT_CLASS_TAG_PREFIX)),
            ], 'components');

            //Add package commands here
            $this->commands([
                \Aregsar\Converter\Console\Commands\ConverterCommand::class,
            ]);
        }

        //Add package resource loading path code here
        $this->loadRoutesFrom(__DIR__ . '/../routes/converter.php');

        $this->loadViewsFrom(__DIR__ . "/../resources/views", self::CONVERTER_VIEWS_NAMESPACE);

        $this->loadViewComponentsAs(self::CONVERTER_COMPONENT_CLASS_TAG_PREFIX, [
            \Aregsar\Converter\View\Components\Converter::class,
        ]);
    }
}
