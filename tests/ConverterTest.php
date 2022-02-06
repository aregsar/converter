<?php

namespace Aregsar\Converter\Tests;

use Aregsar\Converter\Tests\BaseTestCase;

use Aregsar\Converter\Models\Conversion;

use Illuminate\Foundation\Testing\RefreshDatabase;


class ConverterTest extends BaseTestCase
{

    /**
     * @test
     */
    public function it_converts_from_database()
    {
        Conversion::create(["currency" => "EUR", "amount" => 2]);
        Conversion::create(["currency" => "GBP", "amount" => 3]);

        $amount = \Converter::convertFromDatabase("EUR");

        $this->assertEquals($amount, 2);

        $amount = \Converter::convertFromDatabase("GBP");

        $this->assertEquals($amount, 3);

        $amount = \Converter::convertFromDatabase("XYZ");

        $this->assertEquals($amount, null);
    }
}
