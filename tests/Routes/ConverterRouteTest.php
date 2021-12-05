<?php

namespace Aregsar\Converter\Tests\Routes;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterRouteTests extends BaseTestCase
{
    /*@test*/
    public function test_route_returns_success()
    {
        $routeUrlPrefix = config('acme-converter.route_url_prefix');

        \Converter::shouldReceive("convert")
            ->with("EUR")
            ->once()
            ->andReturn("42");

        $this->get("{$routeUrlPrefix}convert/EUR")
            ->assertOK()
            ->assertViewIs("acme-converter::convert")
            ->assertViewHas("amount", "42")
            ->assertSee("Converter View Result 42");
    }


    /*@test*/
    public function test_route_returns_currency_not_supported_message()
    {
        $routeUrlPrefix = config('acme-converter.route_url_prefix');

        \Converter::shouldReceive("convert")
            ->with("XYZ")
            ->once()
            ->andReturn(null);

        $this->get("{$routeUrlPrefix}convert/XYZ")
            ->assertOK()
            ->assertViewIs("acme-converter::convert")
            ->assertViewHas("amount", "Currency not supported")
            ->assertSee("Converter View Result Currency not supported");
    }
}
