<?php

namespace Aregsar\Converter\Tests;

use Aregsar\Converter\Tests\BaseTestCase;

use Aregsar\Converter\Converter;

class ConverterTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_converts()
    {
        $converter = new Converter(['MRK' => 2]);

        $amount = $converter->convert("MRK");

        $this->assertEquals($amount, 2);
    }
}
