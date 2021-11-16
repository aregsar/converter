<?php

namespace Aregsar\Converter\Tests;

use Orchestra\Testbench\TestCase;

use Aregsar\Converter\ConverterServiceProvider;

abstract class BaseTestCase extends TestCase
{
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
        return [ConverterServiceProvider::class];
    }

    /**
     * This method sets up the environment for tests.
     * Called once before all tests are run
     */
    protected function getEnvironmentSetup($app)
    {
        //we can setup configuration or run database migrations here to setup the test environment
    }

    protected function getPackageAliases($app)
    {
        return [
            'Converter' => \Aregsar\Converter\ConverterFacade::class
        ];
    }
}
