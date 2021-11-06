<?php

namespace Aregsar\Converter;

class Converter
{
    public function __construct(private ?array $currencyMap = null)
    {
        $this->currencyMap = $currencyMap ?? ['EUR' => 42];
    }

    public function convert(string $currency): ?int
    {
        if (!array_key_exists(
            $currency,
            $this->currencyMap
        )) {
            return null;
        }

        return $this->currencyMap[$currency];
    }
}
