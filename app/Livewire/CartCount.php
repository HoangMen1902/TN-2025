<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartCount extends Component
{
    public $count = 0;

    public function getCount()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();
        if ($userId) {
            return CartModel::where('user_id', '=', $userId)->count();
        } else {
            $totalItems = 0;
            $carts = Session::get('carts', []);
            if (isset($carts) && !empty($carts)) {
                if(isset($carts['sku']) && !empty($carts['sku'])) {
                $totalItems += count($carts['sku']);
                }

                if(isset($carts['combo']) && !empty($carts['combo'])) {
                $totalItems += count($carts['combo']);

                }
                return $totalItems;
            }
            return 0;
        }
    }

    public function render()
    {
        $this->count = $this->getCount();
        return view('livewire.cart-count');
    }
}
