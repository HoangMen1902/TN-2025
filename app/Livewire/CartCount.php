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
            return CartModel::where('user_id', '=', 'user_id')->count();
        } else {
            $totalItems = 0;
            $carts = Session::get('carts', []);
            if (isset($carts) && !empty($carts)) {
                $totalItems += count($carts['sku']);
                $totalItems += count($carts['combo']);
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
