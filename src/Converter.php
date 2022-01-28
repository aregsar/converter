<?php

namespace Aregsar\Converter;

use Aregsar\Converter\Models\Conversion;

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

    public function convertFromDatabase(string $currency): ?int
    {
        $this->currencyMap = $this->getCurrencyMapFromDatabase();

        if (!array_key_exists(
            $currency,
            $this->currencyMap
        )) {
            return null;
        }

        return $this->currencyMap[$currency];
    }


    private function getCurrencyMapFromDatabase(): array
    {
        $conversionRates = [];
        $conversions = Conversion::all();

        foreach ($conversions as $conversion) {
            //$conversionRates[$conversion->currency] = $conversion->amount;
            $conversionRates[$conversion->currency] = intval($conversion->amount);
        }

        return $conversionRates;
    }
}
