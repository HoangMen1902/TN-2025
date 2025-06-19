<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;

class CartCount extends Component
{
    public $count = 0;

    public function getCount()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        return CartModel::where(function ($query) use ($sessionId, $userId) {
            $query->where('session_id', $sessionId);
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        })->count();
    }

    public function render()
    {
        $this->count = $this->getCount();
        return view('livewire.cart-count');
    }
}