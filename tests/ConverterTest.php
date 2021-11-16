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
        $amount = \Converter::convert("EUR");

        $this->assertEquals($amount, 42);
    }
}
