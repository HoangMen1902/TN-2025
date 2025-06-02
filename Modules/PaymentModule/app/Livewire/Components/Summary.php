<?php

namespace Modules\PaymentModule\Livewire\Components;

use Livewire\Component;

class Summary extends Component
{
    public $carts;
    public function render()
    {
        return view('paymentmodule::livewire.components.summary');
    }
}
