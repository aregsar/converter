<?php

namespace Aregsar\Converter\Tests\Http\Controllers;

use Aregsar\Converter\Tests\BaseTestCase;

class ConversionControllerTest extends BaseTestCase
{
    /*@test*/
    public function test_route_returns_success()
    {
        $routeUrlPrefix = config("acme-converter.route_url_prefix");

        $this->get("{$routeUrlPrefix}conversion/convert/EUR")
            ->assertOK()
            ->assertSee("42");
    }
}
