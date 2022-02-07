<?php

namespace Aregsar\Converter;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class ConverterServiceProvider extends ServiceProvider
{
    const CONVERTER_CONFIG_KEY = 'acme-converter';
    const CONVERTER_VIEWS_NAMESPACE = 'acme-converter';

    //no dash between acme and converter words is intentional
    const CONVERTER_COMPONENT_CLASS_TAG_PREFIX = 'acmeconverter';

    /**
     * Register the application services.
     */
    public function register()
    {
        //Add configuration loading code here
        $this->mergeConfigFrom(__DIR__ . '/../config/converter.php', self::CONVERTER_CONFIG_KEY);

        $this->registerPackageServices();
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            //$this->callBootMethodsRunningInConsole();

            //replace the callBootMethodsRunningInConsole with the following to optimize the call
            $this->callBootMethodsRunningInConsoleOptimized();
        }

        $this->callBootMethods();
    }

    /**
     * Register services provided by this package with the Laravel container
     */
    private function registerPackageServices()
    {
        $this->app->singleton('converter', function ($app) {
            return new Converter();
        });
    }

    /**
     * Bootstrap the application services that only need to be bootstraopped when Laravel is run from the console.
     */
    private function callBootMethodsRunningInConsole()
    {
        //Add package commands
        $this->artisanCommands();

        //Add package resource publishing code

        $this->publishesConfiguration();

        $this->publishesViews();

        $this->publishesViewComponentClasses();

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


    /**
     * Bootstrap the application services that only need to be bootstraopped when Laravel is run from the console.
     * This method is a optional optimized version of the callBootMethodsRunningInConsole method
     */
    private function callBootMethodsRunningInConsoleOptimized()
    {
        //Add package commands
        $this->artisanCommands();


        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Console\Events\CommandStarting $command) {
            echo "\ncallBootMethodsRunningInConsoleOptimized\n";

            if ($command->command === "vendor:publish") {
                echo "\n$command->command\n";
                //Add package resource publishing code

                $this->publishesConfiguration();

                $this->publishesViews();

                $this->publishesViewComponentClasses();

                // Add package migration file publishing
                $this->publishesMigrations();
            }
        });

        // Instead of calling  publishesMigrations we can uncomment line below to run migrations directly from this package when the artisan migrate command is run from the command line in the application that includes this package
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Instructions if you decide to use loadMigrationsFrom instead of publishesMigrations:
        // loadMigrationsFrom inform Laravel to load and run migration files from the database/migrations
        // package directory when we run php artisan migrate from the application that includes this package.
        // Therefore the migration file names must begin with a standard migration timestamp format and
        // you must remove the .stub file extension so the files end with .php extension
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    /**
     * Bootstrap the application services that need to always be bootstrapped.
     */
    private function callBootMethods()
    {
        //uncomment this line only if using classic factories, otherwise you can remove it
        // \Illuminate\Database\Eloquent\Factory::load(__DIR__.'/../database/factories');

        //Add package middleware here
        $this->loadMiddleware($this->app->make(\Illuminate\Routing\Router::class));

        //Add package resource loading path code here
        $this->loadRoutesFrom(__DIR__ . '/../routes/converter.php');

        $this->loadViewsFrom(__DIR__ . "/../resources/views", self::CONVERTER_VIEWS_NAMESPACE);

        $this->aliasAnonymousViewComponentViews();

        $this->loadViewComponents();

        $this->loadLivewireComponents();
    }

    /**
     * Bootstrap the artsian command provided by the package
     */
    private function artisanCommands()
    {
        //Add package commands here
        $this->commands([
            \Aregsar\Converter\Console\Commands\ConverterCommand::class,
        ]);
    }

    /**
     * Bootstrap the publishing of the package configuration file.
     */
    private function publishesConfiguration()
    {
        $this->publishes([
            __DIR__ . '/../config/converter.php' => config_path(self::CONVERTER_CONFIG_KEY . '.php'),
        ], 'acme.config');
    }

    /**
     * Bootstrap the publishing of the package view files.
     * publishes all views including view component and livewire views
     */
    private function publishesViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/' . self::CONVERTER_VIEWS_NAMESPACE)
        ], "acme.views");
    }

    /**
     * Bootstrap the publishing of the package view component class files
     */
    private function publishesViewComponentClasses()
    {
        $this->publishes([
            __DIR__ . '/../src/View/Components/' => app_path('View/Components/' . ucfirst(self::CONVERTER_COMPONENT_CLASS_TAG_PREFIX)),
        ], 'acme.components');
    }


    /**
     * alias anonymous (simple) component view file tag name
     * allows an arbitrary alias string to be used as the blade component tag name instead of
     * the more verbose string format that refers to the view file of the component
     */
    private function aliasAnonymousViewComponentViews()
    {
        \Illuminate\Support\Facades\Blade::component(
            "acme-converter::components.converter.simple", //component view file tag name
            "acme-converter-simple" //tag name alias
        );
    }


    /**
     * Inform Laravel where to load package view components classes from
     * Also informs Laravel the prefix in the tag name that refers to the component class from the package
     */
    private function loadViewComponents()
    {
        //load class based view component classes placed directly in the \Aregsar\Converter\View\Components\ directory
        //The loadViewComponentsAs call will not work if the view component class is placed in a sub directory of \Aregsar\Converter\View\Components\ directory or other custom directory)
        $this->loadViewComponentsAs(
            self::CONVERTER_COMPONENT_CLASS_TAG_PREFIX,
            [
                \Aregsar\Converter\View\Components\Converter::class,
            ]
        );

        //To place view component class in a custom directory
        //explicitly register class based view component classes in custom directories here
        \Illuminate\Support\Facades\Blade::component(
            "acme-converter-conversion-convert", //component tagname
            \Aregsar\Converter\View\Components\Custom\Converter::class
        );
    }



    /**
     * register livewire components here
     */
    private function loadLivewireComponents()
    {
        \Livewire\Livewire::component(
            'show-amount',
            \Aregsar\Converter\Http\Livewire\ShowAmount::class
        );
    }


    /**
     * Load middleware provided by the package
     */
    private function loadMiddleware(\Illuminate\Routing\Router $router)
    {
        //Uncomment this if you want to load the middleware globally for all requests
        //$kernel = $this->app->make(\Illuminate\Foundation\Http\Kernel::class);
        //$kernel->pushMiddleware(\Aregsar\Converter\Http\Middleware\Wrap::class);
        //// \Illuminate\Facade\Http\Kernel::pushMiddleware(\Aregsar\Converter\Http\Middleware\Wrap::class);

        //Or Uncomment this if you want to load as an individual middleware to apply to a route or a controller using an alias
        //$router->aliasMiddleware('wrap', \Aregsar\Converter\Http\Middleware\Wrap::class);
        //use the facade (must add the illuminate facades using statement)
        //// \Illuminate\Routing\Facade\Router::aliasMiddleware('wrap', \Aregsar\Converter\Http\Middleware\Wrap::class);

        //Or Uncomment this if you want to add middleware to configured middleware group
        $routeMiddlewareGroup = config('acme-converter.route_middleware_group');
        $router->pushMiddlewareToGroup("web", \Aregsar\Converter\Http\Middleware\Wrap::class);
        //$router->pushMiddlewareToGroup($routeMiddlewareGroup, \Aregsar\Converter\Http\Middleware\Wrap::class);
        //use the facade (must add the illuminate facades using statement)
        //// \Illuminate\Support\Facades\Route::pushMiddlewareToGroup("web", \Aregsar\Converter\Http\Middleware\Wrap::class);
        //\Illuminate\Support\Facades\Route::pushMiddlewareToGroup($routeMiddlewareGroup, \Aregsar\Converter\Http\Middleware\Wrap::class);
    }


    /**
     * Bootstrap the publishing of the package migration files
     * this method will add the migration file timestamp and copy the file to the appication
     * databse/migrations directory when we run vendor:publish with the "migrations" tag
     */
    private function publishesMigrations()
    {
        $filesystem = $this->app->make(Filesystem::class);

        $migrationFiles = [];

        //we only publish if none of the migration files have been published
        //we must delete all the published files to be able to re-publish
        if ($this->isMigrationFilePublished("create_acmeconversions_table", $filesystem)) return;
        if ($this->isMigrationFilePublished("create_acmenotes_table", $filesystem)) return;

        //////////////////////////////////////////////
        // cd vendor/orchestra/testbench-core/laravel/database/migrations
        // touch vendor/orchestra/testbench-core/laravel/database/migrations/2022_01_27_223338_create_acmenotes_table.php
        // rm vendor/orchestra/testbench-core/laravel/database/migrations/2022_01_27_223338_create_acmenotes_table.php
        //////////////////////////////////////////////

        //echo "\n migration files do not exist\n";
        // for named class migration files can alternativly use isMigrationClassPublished instead of isMigrationFilePublished
        //if($this->isMigrationClassPublished("CreateAcmeconversionsTable")) return;
        //if($this->isMigrationClassPublished('CreateNotesTable')) return;

        //if we got here, none of the files are currently published to we can add them to list of files to publish
        $this->addMigrationFileToArray("create_acmeconversions_table", $migrationFiles);
        //keeping migrations in order example: add 1 second to the next migration file time stamp
        $this->addMigrationFileToArray("create_acmenotes_table", $migrationFiles, 1);
        //keep incrementing the number by one for each subsequent migration file we add


        //var_dump($migrationFiles);
        //var_dump($this->app->databasePath());

        //finally we get to publish the files using the "migrations" vendor:publish tag
        $this->publishes($migrationFiles, 'acme.migrations');
    }


    /**
     * Helper for publishesMigrations method
     */
    private function addMigrationFileToArray(
        $migrationFile,
        &$migrationFiles,
        $runOrder = 0
    ) {

        //$timeZone = new \DateTimeZone('America/Los_Angeles');
        //$date = new \DateTime("now", $timeZone);
        //$date = new \DateTime("NOW");
        $date = new \DateTime("now");

        if ($runOrder > 0) {

            //add a period of seconds to maintain timestamp order
            $date->add(new \DateInterval("PT{$runOrder}S"));
        }

        $timestamp = $date->format("Y_m_d_His");

        $migrationFiles[__DIR__ . "/../database/migrations/{$migrationFile}.php.stub"]
            = database_path("migrations/{$timestamp}_{$migrationFile}.php");

        // Note: runningInConsole is true for when running phpunit tests
        // so database_path() result in the base path below when running phpunit tests:
        // ~/path/to/converter/vendor/orchestra/testbench-core/laravel/database/
    }


    /**
     * Helper for publishesMigrations method
     */
    private function isMigrationFilePublished($migrationFile, $filesystem): bool
    {
        //checks if file does not exist in the application migration folder
        $matchingFilePaths = Collection::make(database_path('migrations/'))
            //$matchingFilePaths = Collection::make($this->app->databasePath() . '/migrations/')
            ->flatMap(function ($path) use ($migrationFile, $filesystem) {
                //Find path names matching given pattern.
                return $filesystem->glob($path . "*_{$migrationFile}.php");
            });

        if (!$matchingFilePaths->isEmpty()) {
            //file exists

            $fullMigrationfileName = $matchingFilePaths->first();

            //if(\Illuminate\Support\Facades\App::runningUnitTests()) {
            // if (!$this->app->runningUnitTests()) {
            //     echo "Migration file $fullMigrationfileName is published, remove before re-publishing\n";
            // }

            return true;
        }

        return false;
    }

    /**
     * Helper for publishesMigrations method
     */
    private function isMigrationClassPublished($migrationClass): bool
    {
        if (class_exists($migrationClass)) {
            // if (!$this->app->runningUnitTests()) {
            //     echo "Migration class $migrationClass is published, remove before re-publishing\n";
            // }
            return true;
        }

        return false;
    }
}
