<?php

namespace Aregsar\Converter\Console\Commands;

use Illuminate\Console\Command;

class ConverterCommand extends Command
{
    protected $description = "Given a currency type prints its converted value in dollars";

    public function __construct()
    {
        //use the package configuration setting to set the command signature
        //acme-converter is our root configuration key under which all settings reside
        $commandPrefix = config('acme-converter.command_prefix');

        $this->signature = "$commandPrefix:converter:convert {currency}";

        parent::__construct();
    }

    public function handle()
    {
        //we will use the facade we created in a previous chapter to reach into the Laravel container
        //get us the Converter class instance and call the convert() method on it.
        $this->info(
            \Aregsar\Converter\ConverterFacade::convert($this->argument("currency"))
        );
    }
}
