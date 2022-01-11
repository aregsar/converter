<?php

namespace Aregsar\Converter\Http\Livewire;

use Livewire\Component;

class ShowAmount extends Component
{
    public $amount;

    public function mount($amount)
    {
        $this->amount = $amount;
    }

    public function render()
    {
        return view('acme-converter::livewire.show-amount');
    }

    public function fix()
    {
        $this->amount = 43;
    }
}
