<?php

namespace Modules\CartModule\Livewire\Component;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\Flashsale;
use App\Models\FlashsaleProduct;
use App\Models\Product;
use App\Models\ProductCombo;
use App\Models\ProductSku;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class Cart extends Component
{

    public $quantities = [];
    public $total_price = 0;
    public $selected_cart = [];

    public $cartItems;

    public $logged_in;

    public array $selected_cart_combos = [];
    public array $selected_cart_skus = [];
    public $productInFlashsale;

    public $flashsaleProduct;



    public function mount()
    {
        $this->productInFlashsale = collect([]);
        $userId = Auth::id();
        $this->logged_in = $userId ? true : false;
        $this->cartItems = collect();
        $now = Carbon::now();
        if ($userId) {
            $skuIds = [];
            $this->cartItems = CartModel::with(['sku.product', 'combo'])
                ->where('user_id', '=', $userId)->get();
            foreach ($this->cartItems as $item) {
                $this->quantities[$item->id] = $item->quantity;
                if($item->item_type === 'sku') {
                    $skuIds[] = $item->sku_id;
                };
            }


            $flashsales = FlashsaleProduct::whereIn('sku_id', $skuIds)->with('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })->get();

            if ($flashsales) {
                $flashsale_map = $flashsales->mapWithKeys(function ($item, $index) {
                    $flashsale = $item->flashsale;
                    if (!$flashsale) return [];
                    return [
                        $index => [
                            'sku_id' => $item->sku_id,
                            'flashsale_id' => $item->flashsale_id,
                            'flashsale_started_at' => $item->flashsale->started_at,
                            'flashsale_expired_at' => $item->flashsale->expired_at,
                            'discount_type' => $item->flashsale->discount_type,
                            'discount_amount' => $item->flashsale->discount_amount
                        ]
                    ];
                });
                $this->productInFlashsale = $flashsale_map;
            }
        } else {
            $cart = Session::get('carts', []);
            $skuIds = array_keys($cart['sku'] ?? []);
            $comboIds = array_keys($cart['combo'] ?? []);
            $skus = ProductSku::with('product')->whereIn('id', $skuIds)->get()->keyBy('id');
            $combos = ProductCombo::whereIn('id', $comboIds)->get()->keyBy('id');
            $this->cartItems['sku'] = $skus;
            $this->cartItems['combo'] = $combos;

            $flashsales = FlashsaleProduct::whereIn('sku_id', $skuIds)->with('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })->get();

            if ($flashsales) {
                $flashsale_map = $flashsales->mapWithKeys(function ($item, $index) {
                    $flashsale = $item->flashsale;
                    if (!$flashsale) return [];
                    return [
                        $index => [
                            'sku_id' => $item->sku_id,
                            'flashsale_id' => $item->flashsale_id,
                            'flashsale_started_at' => $item->flashsale->started_at,
                            'flashsale_expired_at' => $item->flashsale->expired_at,
                            'discount_type' => $item->flashsale->discount_type,
                            'discount_amount' => $item->flashsale->discount_amount
                        ]
                    ];
                });
                $this->productInFlashsale = $flashsale_map;
            }


            if (!$cart || empty($cart)) {
                return;
            }

            if (isset($cart['sku']) && !empty($cart['sku'])) {
                foreach ($cart['sku'] as $index => $item) {
                    $this->quantities['sku'][$index] = $item['quantity'];
                }
            }


            if (isset($cart['combo']) && !empty($cart['combo'])) {
                foreach ($cart['combo'] as $index => $item) {
                    $this->quantities['combo'][$index] = $item['quantity'];
                }
            }
        }
    }

    public function checkFlashsale($sku_id)
    {
        $now = Carbon::now();
        if(!isset($sku_id)) {
            return false;
        }
        $flashSaleProduct = FlashsaleProduct::with(['sku', 'flashsale'])
            ->where('sku_id', $sku_id)
            ->whereHas('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })->first();

        if ($flashSaleProduct) {
            $this->flashsaleProduct = $flashSaleProduct;
            return true;
        }
        return false;
    }

    #[On('selected_cart')]
    public function updatePrice()
    {
        if ($this->logged_in) {
            $this->total_price = 0;
            if (empty($this->selected_cart)) {
                return;
            }

            foreach ($this->selected_cart as $cart) {
                $cartData = CartModel::find($cart);
                $itemType = $cartData->item_type ?? 'sku';
                if ($itemType === "sku") {
                    if ($this->checkFlashsale($cartData?->sku_id ?? null)) {
                        $flashsale = $this->flashsaleProduct->flashsale;
                        $flashsaleType = $flashsale->discount_type;
                        $flashsaleAmount = $flashsale->discount_amount;
                        $minusPice = 0;
                        $originalPrice = ($cartData->sku->sale_price ?? $cartData->sku->price);
                        if ($flashsaleType === 'percent') {
                            $minusPice = $originalPrice * ($flashsaleAmount / 100);
                            if ($minusPice < 0) {
                                $minusPice = 0;
                            }
                        } elseif ($flashsaleType === 'specific') {
                            $minusPice = $flashsaleAmount;
                        }
                        $this->total_price += (($cartData->sku->sale_price ?? $cartData->sku->price) * $cartData->quantity) - $minusPice;
                    } else {
                        $this->total_price += ($cartData->sku->sale_price ?? $cartData->sku->price) * $cartData->quantity;
                    }
                } elseif ($itemType === "combo") {
                    $this->total_price += $cartData->combo->sale_price * $cartData->quantity;
                }
            }
        } else {
            $skuQuantities = collect($this->quantities['sku'] ?? [])
                ->only($this->selected_cart_skus)
                ->toArray();

            $comboQuantities = collect($this->quantities['combo'] ?? [])
                ->only($this->selected_cart_combos)
                ->toArray();


            $skus = ProductSku::whereIn('id', array_keys($skuQuantities))->get();
            $combos = ProductCombo::whereIn('id', array_keys($comboQuantities))->get();


            $totalSku = $skus->sum(function ($sku) use ($skuQuantities) {
                $price = $sku->sale_price ?? $sku->price;
                $quantity = $skuQuantities[$sku->id] ?? 0;
                if ($this->checkFlashsale($sku->id)) {
                    $flashsale = $this->flashsaleProduct->flashsale;
                    $flashsaleType = $flashsale->discount_type;
                    $flashsaleAmount = $flashsale->discount_amount;
                    $minusPice = 0;
                    if ($flashsaleType === 'percent') {
                        $minusPice = $price * ($flashsaleAmount / 100);
                        if ($minusPice < 0) {
                            $minusPice = 0;
                        }
                        $price = $price - $minusPice;
                    } elseif ($flashsaleType === 'specific') {
                        $minusPice = $flashsaleAmount;
                        $price = $price - $minusPice;
                    }
                }
                return $price * $quantity;
            });

            $totalCombo = $combos->sum(function ($combo) use ($comboQuantities) {
                $price = $combo->sale_price ?? $combo->price;
                $quantity = $comboQuantities[$combo->id] ?? 0;
                return $price * $quantity;
            });

            $this->total_price = $totalCombo + $totalSku;
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



    public function updatedQuantities($value, $key)
    {
        if ($this->logged_in) {
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
    }

    public function removeItem($itemId, $type = null)
    {
        if ($this->logged_in) {
            CartModel::where('id', $itemId)->delete();
            unset($this->quantities[$itemId]);
            unset($this->selected_cart[$itemId]);
            $this->dispatch('selected_cart');


            activity()
                ->causedBy(Auth::user())
                ->withProperties(['cart_item_id' => $itemId])
                ->log('Xóa sản phẩm khỏi giỏ hàng');
        } else {
            if (!$type) {
                return;
            }
            $carts = Session::get('carts', []);
            if ($type === 'sku') {
                Arr::forget($carts, 'sku.' . $itemId);
            } elseif ($type === 'combo') {
                Arr::forget($carts, 'combo.' . $itemId);
            }
            Session::put('carts', $carts);
            $this->takeSessionCart();
            $this->dispatch('selected_cart');
        }
    }

    public function takeSessionCart()
    {
        $carts = Session::get('carts', []);
        $skuIds = array_keys($carts['sku'] ?? []);
        $comboIds = array_keys($carts['combo'] ?? []);

        $skus = ProductSku::with('product')->whereIn('id', $skuIds)->get()->keyBy('id');
        $combos = ProductCombo::whereIn('id', $comboIds)->get()->keyBy('id');

        $this->cartItems['sku'] = $skus;
        $this->cartItems['combo'] = $combos;
    }

    public function deleteAll()
    {
        if ($this->logged_in) {
            $userId = Auth::id();

            CartModel::where('user_id', '=', $userId)->delete();

            $this->quantities = [];
            $this->total_price = 0;

            activity()
                ->causedBy(Auth::user())
                ->log('Xóa toàn bộ giỏ hàng');
        } else {
            Session::forget('carts');
        }
    }
    public function increaseQuantity($itemId, $type = null)
    {
        if ($this->logged_in) {
            $cart = CartModel::where('id', $itemId)->first();
            $itemType = $cart ? $cart->item_type : false;
            $quantity = 0;
            if ($itemType === 'sku') {
                $quantity = $cart->sku->quantity;
            } elseif ($itemType === 'combo') {
                $quantity = $cart->combo->quantity;
            } else {
                return;
            }

            if ($this->quantities[$itemId] + 1 > $quantity) {
                return;
            }
            $this->quantities[$itemId] = ($this->quantities[$itemId] ?? 1) + 1;
            $cart->quantity = $this->quantities[$itemId];
            $cart->save();
            $this->dispatch('selected_cart');
        } else {
            if ($type === null) {
                return;
            }
            $carts = Session::get('carts', []);
            if ($type === 'sku') {
                $sku = ProductSku::with(['product' => function ($query) {
                    $query->where('product_status', '!=', 'inactive');
                }])->where('id', $itemId)->first();

                if (!$sku) return;

                $newQuantity = $carts['sku'][$itemId]['quantity'] + 1;

                if ($sku->quantity < $newQuantity) {
                    $this->dispatch('toast', type: 'error', message: 'Không đủ số lượng sản phẩm.');
                    return;
                }

                $carts['sku'][$itemId]['quantity'] = $newQuantity;
                $this->quantities['sku'][$itemId] = $newQuantity;
                $this->dispatch('selected_cart');
            } elseif ($type === 'combo') {
                $combo = ProductCombo::where('id', $itemId)
                    ->where('expired_at', '>', now())
                    ->first();

                if (!$combo) return;

                $newQuantity = $carts['combo'][$itemId]['quantity'] + 1;

                if ($combo->quantity < $newQuantity) {
                    $this->dispatch('toast', 'Không đủ số lượng sản phẩm');
                    return;
                }

                $carts['combo'][$itemId]['quantity'] = $newQuantity;
                $this->quantities['combo'][$itemId] = $newQuantity;
                $this->dispatch('selected_cart');
            } else {
                return;
            }

            Session::put('carts', $carts);
        }
    }

    public function decreaseQuantity($itemId, $type = null)
    {
        if ($this->logged_in) {
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
                    $this->dispatch('toast', 'Sản phẩm này đã hết');
                    return;
                }
                $this->quantities[$itemId] = ($this->quantities[$itemId] ?? 1) - 1;
                $cart->quantity = $this->quantities[$itemId];
                $cart->save();
                $this->dispatch('selected_cart');
            }
        } else {
            if ($type === null) {
                return;
            }
            $carts = Session::get('carts', []);
            if ($type === 'sku') {
                $sku = ProductSku::with(['product' => function ($query) {
                    $query->where('product_status', '!=', 'inactive');
                }])->where('id', $itemId)->first();

                if (!$sku) return;

                $newQuantity = $carts['sku'][$itemId]['quantity'] - 1;
                if ($newQuantity < 1) {
                    $this->dispatch('toast', type: 'error', message: 'Đã đạt mức sản phẩm tối thiểu');
                    return;
                }
                if ($sku->quantity < $newQuantity) {
                    $this->dispatch('toast', type: 'error', message: 'Không đủ số lượng sản phẩm.');
                    return;
                }

                $carts['sku'][$itemId]['quantity'] = $newQuantity;
                $this->quantities['sku'][$itemId] = $newQuantity;
                $this->dispatch('selected_cart');
            } elseif ($type === 'combo') {
                $combo = ProductCombo::where('id', $itemId)
                    ->where('expired_at', '>', now())
                    ->first();

                if (!$combo) return;

                $newQuantity = $carts['combo'][$itemId]['quantity'] - 1;
                if ($newQuantity < 1) {
                    $this->dispatch('toast', type: 'error', message: 'Đã đạt mức sản phẩm tối thiểu');
                    return;
                }
                if ($combo->quantity < $newQuantity) {
                    $this->dispatch('toast', 'Không đủ số lượng sản phẩm');
                    return;
                }

                $carts['combo'][$itemId]['quantity'] = $newQuantity;
                $this->quantities['combo'][$itemId] = $newQuantity;
                $this->dispatch('selected_cart');
            } else {
                return;
            }

            Session::put('carts', $carts);
        }
    }


    public function render()
    {
        return view('cartmodule::livewire.component.cart');
    }
}
