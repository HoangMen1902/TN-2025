<?php

namespace Modules\CartModule\Livewire\Component;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Cart extends Component
{

    public $quantities = [];
    public $total_price = 0;
    public $selected_cart = [];


    #[On('selected_cart')]
    public function updatePrice()
    {
        $this->total_price = 0;
        if (empty($this->selected_cart)) {
            return;
        }
        foreach ($this->selected_cart as $cart) {
            $cartData = CartModel::find($cart);
            $itemType = $cartData->item_type;
            if ($itemType === "sku") {
                $this->total_price += ($cartData->sku->sale_price ?? $cartData->sku->price) * $cartData->quantity;
            } elseif ($itemType === "combo") {
                $this->total_price += $cartData->combo->sale_price * $cartData->quantity;
            }
        }
    }

    public function getCartCount()
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

    private function checkUserCart($cartId): bool
    {
        $cart = CartModel::find($cartId)->first();
        if (!$cart) {
            return false;
        }
        if (Auth::check() && $cart->user_id == Auth::user()->id) {
            return true;
        } elseif (!Auth::check() && $cart->session_id === session()->getId()) {
            return true;
        }
        return false;
    }



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
        $this->dispatch('selected_cart');


        activity()
            ->causedBy(Auth::user())
            ->performedOn(CartModel::find($key))
            ->withProperties(['quantity' => $value])
            ->log('Cập nhật số lượng sản phẩm trong giỏ hàng');
    }

    public function removeItem($itemId)
    {
        CartModel::where('id', $itemId)->delete();
        unset($this->quantities[$itemId]);
        $this->dispatch('selected_cart');


        activity()
            ->causedBy(Auth::user())
            ->withProperties(['cart_item_id' => $itemId])
            ->log('Xóa sản phẩm khỏi giỏ hàng');
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
        $this->total_price = 0;

        activity()
            ->causedBy(Auth::user())
            ->log('Xóa toàn bộ giỏ hàng');
    }
    public function increaseQuantity($itemId)
    {
        $cart = CartModel::where('id', $itemId)->first();
        $itemType = $cart->item_type;
        $quantity = 0;
        if ($itemType === 'sku') {
            $quantity = $cart->sku->quantity;
        } elseif ($itemType === 'combo') {
            $quantity = $cart->combo->quantity;
        }

        if ($this->quantities[$itemId] + 1 > $quantity) {
            return;
        }
        $this->quantities[$itemId] = ($this->quantities[$itemId] ?? 1) + 1;
        $cart->quantity = $this->quantities[$itemId];
        $cart->save();
        $this->dispatch('selected_cart');
    }

    public function decreaseQuantity($itemId)
    {
        $current = $this->quantities[$itemId] ?? 1;
        if ($current > 1) {
            $cart = CartModel::where('id', $itemId)->first();
            $itemType = $cart->item_type;
            $quantity = 0;
            if ($itemType === 'sku') {
                $quantity = $cart->sku->quantity;
            } elseif ($itemType === 'combo') {
                $quantity = $cart->combo->quantity;
            }

            if ($this->quantities[$itemId] - 1 > $quantity) {
                return; //bat loi sau
            }
            $this->quantities[$itemId] = ($this->quantities[$itemId] ?? 1) - 1;
            $cart->quantity = $this->quantities[$itemId];
            $cart->save();
            $this->dispatch('selected_cart');
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


        return view('cartmodule::livewire.component.cart', [
            'cartItems' => $cartItems,
        ]);
    }
}
