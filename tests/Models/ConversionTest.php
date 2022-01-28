<?php

namespace Aregsar\Converter\Tests\Models;

use Aregsar\Converter\Models\Conversion;
use Aregsar\Converter\Tests\BaseTestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversionTest extends BaseTestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_creates_conversion()
    {
        //Example: using the factory and overriding creation data to create the test model
        $conversion = Conversion::factory()
            ->create([
                "currency" => "EUR",
                "amount" => 2
            ]);

        $this->assertDatabaseCount("acmeconversions", 1);
        $this->assertEquals($conversion->currency, "EUR");
        $this->assertEquals($conversion->amount, 2);
    }


    /** @test */
    public function it_creates_conversions()
    {
        //Example: using the model directly to create the test model
        $conversion1 = Conversion::create(["currency" => "EUR", "amount" => 2]);
        $conversion2 = Conversion::create(["currency" => "GBP", "amount" => 3]);


        $this->assertDatabaseCount("acmeconversions", 2);
        $this->assertEquals($conversion1->currency, "EUR");
        $this->assertEquals($conversion1->amount, 2);
        $this->assertEquals($conversion2->currency, "GBP");
        $this->assertEquals($conversion2->amount, 3);
    }

    /** @test */
    public function it_gets_all_models()
    {
        Conversion::create(["currency" => "EUR", "amount" => 2]);
        Conversion::create(["currency" => "GBP", "amount" => 3]);

        $models = Conversion::all();
        $this->assertCount(2, $models);
    }
}
