<?php

namespace Aregsar\Converter\Tests\Console\Commands;

use Aregsar\Converter\Tests\BaseTestCase;

class ConverterCommandTest extends BaseTestCase
{

    /**
     * @test
     */
    public function the_command_works()
    {
        \Aregsar\Converter\ConverterFacade::shouldReceive("convert")
            ->with("EUR")
            ->once()
            ->andReturn("42");

        $this->artisan("converter:convert EUR")
            ->expectsOutput("42\n")
            ->assertExitCode(0);
    }
}
