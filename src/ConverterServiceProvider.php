<?php

namespace Aregsar\Converter;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

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

            // Add package migration file publishing
            $this->publishesMigrations();
            // Or uncomment line below and comment out publishesMigrations to run migrations directly from this package
            // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Instructions if you decide to use loadMigrationsFrom instead of publishesMigrations:
            // loadMigrationsFrom inform Laravel to load and run migration files from the database/migrations
            // package directory when we run php artisan migrate from the application that includes this package.
            // Therefore the migration file names must begin with a standard migration timestamp format and
            // you must remove the .stub file extension so the files end with .php extension
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
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


    //this method will add the migration file timestamp and copy the file to the appication
    //databse/migrations directory when we run vendor:publish with the "migrations" tag
    private function publishesMigrations()
    {
        $filesystem = $this->app->make(Filesystem::class);

        $migrationFiles = [];

        //we only publish if none of the migration files have been published
        //we must delete all the published files to be able to re-publish
        if ($this->isMigrationFilePublished("create_acme_conversions_table", $filesystem)) return;
        //if($this->isMigrationFilePublished("create_acme_notes_table", $fileSystem))return;

        // for named class migration files can alternativly use isMigrationClassPublished instead of isMigrationFilePublished
        //if($this->isMigrationClassPublished("CreateAcmeconversionsTable")) return;
        ////if($this->isMigrationClassPublished('CreateNotesTable')) return;

        //if we got here, none of the files are currently published to we can add them to list of files to publish
        $this->addMigrationFileToArray("create_acme_conversions_table", $migrationFiles);
        //$this->addMigrationFileToArray("create_acme_notes_table", $migrationFiles, 1);

        //finally we get to publish the files using the "migrations" vendor:publish tag
        $this->publishes($migrationFiles, 'migrations');
    }


    private function addMigrationFileToArray($migrationFile,  $migrationFiles, $runOrder = 0)
    {
        //$timestamp = date("Y_m_d_His", time());
        $date = new \DateTime("NOW");

        if ($runOrder > 0) {
            //add a period of seconds to maintain timestamp order
            $date->add(new \DateInterval("P${$runOrder}S"));
        }

        $timestamp = $date->format("Y-m-d H:i:s");

        $migrationFiles[__DIR__ . "/../database/migrations/{$migrationFile}.php.stub"]
            = database_path("migrations/{$timestamp}_{$migrationFile}.php");
    }


    private function isMigrationFilePublished($migrationFile, $filesystem): bool
    {
        //checks if file does not exist in the application migration folder
        //$matchingFilePaths = Collection::make(database_path('migrations'))
        $matchingFilePaths = Collection::make($this->app->databasePath() . '/migrations/')
            ->flatMap(function ($path) use ($migrationFile, $filesystem) {
                //Find path names matching given pattern.
                return $filesystem->glob($path . "*_{$migrationFile}.php");
            });

        if (!$matchingFilePaths->empty()) {
            //file exists
            $fullMigrationfileName = $matchingFilePaths->first();
            echo "$fullMigrationfileName migration file is published, remove before re-publishing";
            return true;
        }

        return false;
    }


    private function isMigrationClassPublished($migrationClass): bool
    {
        if (class_exists($migrationClass)) {
            echo "$migrationClass migration class is published, remove before re-publishing";
            return true;
        }

        return false;
    }
}
