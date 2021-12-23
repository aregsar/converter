<?php

namespace Aregsar\Converter\View\Components;

use Illuminate\View\Component;

class Converter extends Component
{
    public function __construct(protected $amount)
    {
        $this->amount = $amount;
    }

    public function render()
    {
        return view('acme-converter::components.conversion.convert', [
            "amount" => $this->amount,
        ]);
    }
}
