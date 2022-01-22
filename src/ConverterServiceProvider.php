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

            //Add package commands here
            $this->commands([
                \Aregsar\Converter\Console\Commands\ConverterCommand::class,
            ]);

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
        }

        $this->loadMiddleware($this->app->make(\Illuminate\Routing\Router::class));
        //Add package resource loading path code here
        $this->loadRoutesFrom(__DIR__ . '/../routes/converter.php');

        $this->loadViewsFrom(__DIR__ . "/../resources/views", self::CONVERTER_VIEWS_NAMESPACE);

        //load view component classes placed directly in the \Acme\Converter\View\Components\ directory
        //(will not work if the component class is placed in a sub directory of \Acme\Converter\View\Components\ directory)
        $this->loadViewComponentsAs(
            self::CONVERTER_COMPONENT_CLASS_TAG_PREFIX,
            [
                \Aregsar\Converter\View\Components\Converter::class,
            ]
        );

        //register livewire components here
        \Livewire\Livewire::component(
            'show-amount',
            \Aregsar\Converter\Http\Livewire\ShowAmount::class
        );

        //register blade components here
        //uncomment this callAfterResolving method if you encounter any issues with the BladeCompiler not being available
        // $this->callAfterResolving(\Illuminate\View\Compilers\BladeCompiler::class, function () {
        //
        //explicitly register component classes in custom directories here
        \Illuminate\Support\Facades\Blade::component(
            "acme-converter-conversion-convert",
            \Aregsar\Converter\View\Components\Custom\Converter::class
        );
        //
        // reversing the parameters will work as well. (see notes below)
        // \Illuminate\Support\Facades\Blade::component(
        //     \Aregsar\Converter\View\Components\Custom\Converter::class,
        //     "acme-converter-conversion-convert"
        // );
        //
        //explicitly register simple component views
        \Illuminate\Support\Facades\Blade::component(
            "acme-converter::components.converter.simple",
            "acme-converter-simple"
        );
        //
        // });

        //$this->loadMiddleware($this->app->make(\Illuminate\Routing\Router::class));
    }

    public function loadMiddleware(\Illuminate\Routing\Router $router)
    {
        //Uncomment this if you want to load the middleware globally for all requests
        //$kernel = $this->app->make(\Illuminate\Foundation\Http\Kernel::class);
        //$kernel->pushMiddleware(\Aregsar\Converter\Http\Middleware\Wrap::class);
        //// \Illuminate\Facade\Http\Kernel::pushMiddleware(\Acme\Converter\Http\Middleware\Wrap::class);

        //Or Uncomment this if you want to load as an individual middleware to apply to a route or a controller using an alias
        //$router = $this->app->make(\Illuminate\Routing\Router::class);
        //$router->aliasMiddleware('wrap', \Acme\Converter\Http\Middleware\Wrap::class);
        //use the facade (must add the illuminate facades using statement)
        //// \Illuminate\Routing\Facade\Router::aliasMiddleware('wrap', \Acme\Converter\Http\Middleware\Wrap::class);

        //Or Uncomment this if you want to add middleware to configured middleware group
        $routeMiddlewareGroup = config('acme-converter.route_middleware_group');
        $router->pushMiddlewareToGroup("web", \Aregsar\Converter\Http\Middleware\Wrap::class);
        //$router->pushMiddlewareToGroup($routeMiddlewareGroup, \Acme\Converter\Http\Middleware\Wrap::class);
        //use the facade (must add the illuminate facades using statement)
        //// \Illuminate\Support\Facades\Route::pushMiddlewareToGroup("web", \Acme\Converter\Http\Middleware\Wrap::class);
        //\Illuminate\Support\Facades\Route::pushMiddlewareToGroup($routeMiddlewareGroup, \Acme\Converter\Http\Middleware\Wrap::class);
    }
}
