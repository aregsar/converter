<?php

namespace Aregsar\Converter\Tests\Http\Controllers;

use Aregsar\Converter\Tests\BaseTestCase;

class ConversionControllerTest extends BaseTestCase
{
    /*@test*/
    public function test_route_returns_success()
    {
        $routeUrlPrefix = config('acme-converter.route_url_prefix');

        \Converter::shouldReceive("convert")
            ->with("EUR")
            ->once()
            ->andReturn("42");

        $this->get("{$routeUrlPrefix}conversion/convert/EUR")
            ->assertOK()
            ->assertViewIs("acme-converter::converter.convert")
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

        $this->get("{$routeUrlPrefix}conversion/convert/XYZ")
            ->assertOK()
            ->assertViewIs("acme-converter::converter.convert")
            ->assertViewHas("amount", "Currency not supported")
            ->assertSee("Converter View Result Currency not supported");
    }
}
