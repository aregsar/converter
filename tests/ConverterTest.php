<?php

namespace Aregsar\Converter\Tests;

use PHPUnit\Framework\TestCase;

use Aregsar\Converter\Converter;

class ConverterTest extends TestCase
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
