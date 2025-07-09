<?php

namespace Modules\PaymentModule\Livewire\Components;

use App\Models\PaymentMethod as ModelsPaymentMethod;
use Livewire\Component;

class PaymentMethod extends Component
{

    public $active_method = [];

    public function mount() {
        $active_method = ModelsPaymentMethod::where('method_status','=','active')->get();
        foreach($active_method as $method) {
            $this->active_method[] = $method->method_name;
        }
    }
    public function render()
    {
        return view('paymentmodule::livewire.components.payment-method');
    }
}
