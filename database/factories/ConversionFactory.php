<?php

namespace Aregsar\Converter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Aregsar\Converter\Models\Conversion;

class ConversionFactory extends Factory
{
    protected $model = Conversion::class;

    public function definition()
    {
        return [
            "currency" => $this->faker->word,
            "amount"   => $this->faker->number,
        ];
    }
}
