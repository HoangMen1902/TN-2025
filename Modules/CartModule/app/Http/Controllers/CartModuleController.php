<?php

namespace Modules\CartModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartModuleController extends Controller
{
    public function index()
    {
        return view('cartmodule::index');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'sku_id' => 'nullable|exists:product_skus,id',
            'combo_id' => 'nullable|exists:product_combos,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$request->sku_id && !$request->combo_id) {
            return redirect()->back()->withErrors(['error' => 'Phải chọn SKU hoặc combo!']);
        }

        $sessionId = session()->getId();
        $userId = Auth::id();

        $itemType = $request->sku_id ? 'sku' : 'combo';
        $itemIdField = $itemType === 'sku' ? 'sku_id' : 'combo_id';
        $itemId = $request->$itemIdField;

        $cartItem = Cart::where('session_id', $sessionId)
            ->where('item_type', $itemType)
            ->where($itemIdField, $itemId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->user_id = $userId;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'sku_id' => $request->sku_id,
                'combo_id' => $request->combo_id,
                'quantity' => $request->quantity,
                'item_type' => $itemType,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Đã thêm vào giỏ hàng!']);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }


   
}
