<?php

namespace Aregsar\Converter\Tests\Routes;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterRouteTests extends BaseTestCase
{
    /*@test*/
    public function test_route_returns_success()
    {
        \Converter::shouldReceive("convert")
            ->with("EUR")
            ->once()
            ->andReturn("42");

        $this->get("convert/EUR")
            ->assertOK()
            ->assertSee("42");
    }


    /*@test*/
    public function test_route_returns_currency_not_supported_message()
    {
        \Converter::shouldReceive("convert")
            ->with("XYZ")
            ->once()
            ->andReturn(null);

        $this->get("convert/XYZ")
            ->assertOK()
            ->assertSee("Currency not supported");
    }
}
