<?php

namespace Acme\Converter;

class Converter
{
    public function __construct(private ?array $currencyMap = null)
    {
        $this->currencyMap ??= ['MRK' => 2];
    }

    public function convert(string $currency): ?int
    {
        return $this->currencyMap[$currency];
    }
}


// class ConverterX
// {
//     public function __construct(private ?array $currencyMap = null)
//     {
//         print "In BaseClass constructor\n";
//     }
// }
