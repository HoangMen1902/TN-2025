<?php

namespace Modules\CartModule\Livewire\Component;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{

    public $quantities = [];

    public function mount()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $cartItems = CartModel::with(['sku.product', 'combo'])
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })->get();

        foreach ($cartItems as $item) {
            $this->quantities[$item->id] = $item->quantity;
        }
    }

    public function updatedQuantities($value, $key)
    {
        if ($value < 1) {
            $this->quantities[$key] = 1;
            $value = 1;
        }
        CartModel::where('id', $key)->update(['quantity' => $value]);
    }

    public function removeItem($itemId)
    {
        CartModel::where('id', $itemId)->delete();
        unset($this->quantities[$itemId]);
    }
    public function deleteAll()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        CartModel::where(function ($query) use ($sessionId, $userId) {
            $query->where('session_id', $sessionId);
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        })->delete();

        $this->quantities = [];
    }
    public function increaseQuantity($itemId)
    {
        $this->quantities[$itemId] = ($this->quantities[$itemId] ?? 1) + 1;
        CartModel::where('id', $itemId)->update(['quantity' => $this->quantities[$itemId]]);
    }

    public function decreaseQuantity($itemId)
    {
        $current = $this->quantities[$itemId] ?? 1;
        if ($current > 1) {
            $this->quantities[$itemId] = $current - 1;
            CartModel::where('id', $itemId)->update(['quantity' => $this->quantities[$itemId]]);
        }
    }


    public function render()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $cartItems = CartModel::with(['sku.product', 'combo'])
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })->get();

        foreach ($cartItems as $item) {
            if (!isset($this->quantities[$item->id])) {
                $this->quantities[$item->id] = $item->quantity;
            }
        }


        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->sku->price ?? 0;
            $quantity = $this->quantities[$item->id] ?? 1;
            $subtotal += $price * $quantity;
        }

        return view('cartmodule::livewire.component.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }
}
