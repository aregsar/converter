<?php

namespace Aregsar\Converter\View\Components\Conversion;

use Illuminate\View\Component;

class Converter extends Component
{
    public function __construct(public int $amount)
    {
        $this->amount = $amount;
    }

    // public function render()
    // {
    //     return view('acme-converter::components.conversion.convert', [
    //         "amount" => $this->amount,
    //     ]);
    // }

    public function render()
    {
        return view('acme-converter::components.conversion.convert');
    }
}
