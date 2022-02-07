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

        // \Converter::shouldReceive("convert")
        //     ->with("EUR")
        //     ->once()
        //     ->andReturn("42");

        $commandPrefix = config("acme-converter.command_prefix");

        $this->artisan("{$commandPrefix}converter:convert EUR")
            ->expectsOutput("42\n")
            ->assertExitCode(0);
    }
}
