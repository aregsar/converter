<?php

namespace Aregsar\Converter\Tests;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_converts()
    {
        $amount = \Aregsar\Converter\ConverterFacade::convert("EUR");

        $this->assertEquals($amount, 42);
    }
}
