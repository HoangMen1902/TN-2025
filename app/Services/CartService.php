<?php

namespace App\Services;

use App\Models\Cart;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;

class CartService
{
    public static function syncCartAfterLogin($userId)
    {


        try {
            $carts = FacadesSession::get('carts', []);

            if (empty($carts) || (empty($carts['sku']) && empty($carts['combo']))) {
                return;
            }


            if (!empty($carts['sku'])) {
                foreach ($carts['sku'] as $skuId => $cartSku) {
                    Cart::updateOrInsert(
                        [
                            'user_id' => $userId,
                            'sku_id' => $skuId,
                            'combo_id' => null,
                        ],
                        [
                            'item_type' => 'sku',
                            'quantity' => DB::raw("quantity + {$cartSku['quantity']}")
                        ]
                    );
                }
            }

            if (!empty($carts['combo'])) {
                foreach ($carts['combo'] as $comboId => $cartCombo) {
                    Cart::updateOrInsert(
                        [
                            'user_id' => $userId,
                            'sku_id' => null,
                            'combo_id' => $comboId,
                        ],
                        [
                            'item_type' => 'combo',
                            'quantity' => DB::raw("quantity + {$cartCombo['quantity']}")
                        ]
                    );
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
