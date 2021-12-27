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
        return view('livewire.show-amount');
        //return view('acme-converter::components.livewire.show-amount');
    }
}
