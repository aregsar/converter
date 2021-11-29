<?php

namespace Aregsar\Converter\Console\Commands;

use Illuminate\Console\Command;

class ConverterCommand extends Command
{
    protected $signature = "converter:convert {currency}";

    protected $description = "Given a currency type prints its converted value in dollars";

    public function handle()
    {
        //we will use the facade we created in a previous chapter to reach into the Laravel container
        //get us the Converter class instance and call the convert() method on it.
        $this->info(
            \Aregsar\Converter\ConverterFacade::convert($this->argument("currency"))
        );
    }
}
