<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public static function syncCartAfterLogin($currCart, $userId)
    {


        try {
            foreach ($currCart as $cart) {

                $existingCart = Cart::where('user_id', $userId)
                    ->where('item_type', $cart->item_type)
                    ->where($cart->item_type === 'sku' ? 'sku_id' : 'combo_id', $cart->sku_id ?? $cart->combo_id)
                    ->first();

                if ($existingCart) {
                    $existingCart->quantity += $cart->quantity;
                    $existingCart->save();

                    $cart->delete();
                } else {
                    $cart->user_id = $userId;
                    $saved = $cart->save();

                    if ($saved) {
                        \Log::info('Cart updated with user_id', [
                            'cart_id' => $cart->id,
                            'user_id' => $userId
                        ]);
                    } else {
                        \Log::warning('Cart save returned false', [
                            'cart_id' => $cart->id,
                            'user_id' => $userId
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi xử lý đồng bộ cart', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
