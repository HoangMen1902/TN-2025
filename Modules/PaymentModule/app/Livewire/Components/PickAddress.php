<?php

namespace Modules\PaymentModule\Livewire\Components;

use Livewire\Component;
use App\Models\CheckoutAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PickAddress extends Component
{
    public $addresses;
    public $selectedAddressId = null;

    public function mount()
    {
        Log::info('🔧 PickAddress mount called'); // ✅ Debug
        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
    }

    public function updated($property, $value)
    {
        if ($property === 'selectedAddressId') {
            Log::info('📌 updated() triggered for selectedAddressId: ' . $value);

          
            $parts = explode('|', $value);

            if ($parts[0] === 'session') {
         
                session()->put('shipping_address', [
                    'customer_name' => $parts[1] ?? '',
                    'phone' => $parts[2] ?? '',
                    'full_address' => $parts[3] ?? '',
                ]);
            } else {
           
                $this->selectAddress($parts[0]);
            }

            $this->selectedAddressId = $value;
        }
    }


    public function selectAddress($id)
    {
        $this->selectedAddressId = $id;
        $address = CheckoutAddress::find($id);

        Log::info('✅ selectAddress called with id=' . $id);
        Log::info('✅ Address data:', $address ? $address->toArray() : ['null' => true]);

        $this->dispatch('address-selected', [
            'selected_id' => $id,
            'customer_name' => $address->customer_name,
            'phone' => $address->phone,
            'email' => $address->email,
            'address' => $address->address,
        ]);
    }



    public function render()
    {

        return view('paymentmodule::livewire.components.pick-address');
    }
}
