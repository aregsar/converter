<?php

namespace Aregsar\Converter\Tests;

use Orchestra\Testbench\TestCase;

use Aregsar\Converter\ConverterServiceProvider;

use Aregsar\Converter\ConverterFacade;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaseTestCase extends TestCase
{

    use RefreshDatabase;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        //Add initialization code here after the parent setUp
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        //Add cleanup code here before the parent tearDown

        parent::tearDown();
    }

    /**
     * This method exposes package service providers to the Orchestra testing fixture.
     */
    protected function getPackageProviders($app)
    {
        //we just have a single service provider at the moment
        //The Orchestra testbench test framework will call the register methods and then the boot methods on
        //these service providers to emulate way Laravel bootstrap flow
        return [
            ConverterServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,
        ];
    }

    /**
     * This method is called before each test.
     * This method registers facade alias mappings with the Laravel aliases array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Converter' => ConverterFacade::class
        ];
    }


    /**
     * This method is called before each test.
     * Can set database configuration and run database migrations here to setup the test environment.
     *
     * Will override any .env.testing and/or phpunit.xml based environment variable configuration settings
     * Note: .env.testing, if used, is loaded by Laravel not Phpunit.
     * If .env.testing is used, phpunit.xml environment variables will override .env.testing variables
     *
     * Orchestra/testbench package loads Laravel and uses Laravel to load the configuration from
     * .env or .env.testing file depending on the phpunit.xml configuration.
     * After Laravel loads the environment variables,
     * any environment variables defined in phpunit.xml are loaded and will override
     * the .env or .env.testing environment variables.
     * For laravel to know to use .env.testing file instead of .env file, at minimum
     * the <env name="APP_ENV" value="testing"> element must be defined in
     * in the <php> section of the phpunit.xml file. Laravel appends the value of this key
     * to ".env." to build the name of the ".env.testing" environment setting file to load.
     * If we set the value to some other value say "phpunit" then Laravel would use it to load a
     * ".env.phpunit" environment setting file. Its only by convention that the value used is "testing".
     */
    protected function getEnvironmentSetup($app)
    {
        //set test database configuration settings
        //$this->configTestDatabase();

        //run migrations after setting up the test database configuration

        //call migrateMySqlDatabase() instead of directly calling migrateDatabase() when using MySQL
        //works in conjunction with the refreshDatabase trait added to this class
        $this->migrateMySqlDatabase();
    }



    private function migrateMySqlDatabase()
    {
        //on the first test run migrate the database, then set migrated to true so the refreshDatabase trait
        //called after this method does not drop the tables (and then try to run migrations which it will not find)
        if (\Illuminate\Foundation\Testing\RefreshDatabaseState::$migrated === false) {

            $this->migrateDatabase();

            \Illuminate\Foundation\Testing\RefreshDatabaseState::$migrated = true;
        }
    }

    private function migrateDatabase()
    {
        //drop all tables (in case there are tables remaining from a previous test run)
        \Illuminate\Support\Facades\Schema::dropAllTables();

        $this->runMigrations();

        //Create the test users table directly because we dont use a migration file
        //for test users
        \Illuminate\Support\Facades\Schema::create("users", function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }


    private function configTestDatabase()
    {
        //Note: as an alternative to setting up the database environmnet here,
        //we can override the .env database settings environment varibales in the phpunit.xml
        //and/or .env.testing file

        //we are adding a new "testdb" connection just for testong
        //Adds a new "testdb" key in the "database.connections" array within the configuration array that
        //was loaded from the "config\database.php" file by laravel.
        //The value of this new key is set to the array that has the sqlite driver and :memory: database settings
        //config()->set('database.connections.testing', [
        config()->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            //'prefix' => '',
        ]);


        //overrides the value of the "database.default" key in the configuration array loaded
        //from the "config\database.php" file by Laravel, to the new "testdb" commection that was added above.
        //Thereby setting the default database connection for testing to "testdb".
        //config()->set('database.default', 'testing');
        config()->set('database.default', 'testdb');
    }


    private function runMigrations()
    {
        // $this->runClassicMigrations();

        $this->runAnonymousClassMigrations();
    }


    private function runClassicMigrations()
    {
        //this is deprecated in Laravel 9 - uses legacy migrations - Laravel 8 and below

        // Include the named migration class definition CreateAcmeConversionsTable from the file into the global namespace
        require_once __DIR__ . "/../database/migrations/create_acmeconversions_table.php.stub";
        require_once __DIR__ . "/../database/migrations/create_acmenotes_table.php.stub";


        (new \CreateAcmeConversionsTable())->up();
        (new \CreateAcmeNotesTable())->up();
    }


    private function runAnonymousClassMigrations()
    {
        //New migrations use anonymous classes optional in laravel 8 default in laravel 9
        //https://stackoverflow.com/questions/5948395/require-include-into-variable
        //https://stackoverflow.com/questions/26257846/why-i-cannot-get-the-return-value-of-require-once-function-in-php
        //the included migration file returns an new-ed up instance of anonymous migration class
        //that we assign to a variable and then call its up method

        // the varidabe $createAcmeConversionsTable refers to the instantated anonymous class returned from the included file
        // we need to use require instead of require_once because after the first call to require_once it will retunr the boolean true
        // instead of the anonymous class
        $createAcmeConversionsTable = require __DIR__ . "/../database/migrations/create_acmeconversions_table.php.stub";
        $createAcmeNotesTable = require __DIR__ . "/../database/migrations/create_acmenotes_table.php.stub";


        $createAcmeConversionsTable->up();
        $createAcmeNotesTable->up();
    }
}
