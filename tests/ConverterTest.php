<?php

namespace Acme\Converter\Tests;

use Illuminate\Support\Facades\App;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_converts()
    {
        //uses the Laravel APP facade to access the DI container
        $converter = App::make("converter");

        $amount = $converter->convert("EUR");

        $this->assertEquals($amount, 42);
    }
}
