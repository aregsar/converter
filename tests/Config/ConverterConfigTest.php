<?php

namespace Aregsar\Converter\Tests\Config;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterConfigTests extends BaseTestCase
{
    /**
     * @test
     */
    public function it_loads_configuration_settings()
    {
        //config()->set("acme-converter.test","123");
        $setting = config("acme-converter.packagename_prefix");

        $this->assertSame("acme-converter", $setting);
    }
}
