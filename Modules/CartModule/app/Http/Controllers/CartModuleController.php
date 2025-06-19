<?php

namespace Modules\CartModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ComboSku;
use App\Models\ProductCombo;
use App\Models\ProductSku;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        if ($request->sku_id && $request->combo_id) {
            return redirect()->back()->with(['error' => 'Chỉ được chọn SKU hoặc combo, không cả hai!']);
        }

        if (!$request->sku_id && !$request->combo_id) {
            return redirect()->back()->with(['error' => 'Phải chọn SKU hoặc combo!']);
        }

        $sessionId = session()->getId();
        $userId = Auth::id();

        $itemType = $request->combo_id ? 'combo' : 'sku';
        $itemIdField = $itemType === 'sku' ? 'sku_id' : 'combo_id';
        $itemId = $request->$itemIdField;

        if (Auth::check()) {

            $cartItem = Cart::where('user_id', $userId)
                ->where('item_type', $itemType)
                ->where($itemIdField, $itemId)
                ->first();
        } else {
            $cartItem = Cart::where('session_id', $sessionId)
                ->where('item_type', $itemType)
                ->where($itemIdField, $itemId)
                ->first();
        }

        $query = [];

        if ($request->sku_id) {
            $sku = ProductSku::find($request->sku_id);
            $query = ['type' => 'sku', 'data' => $sku];
        } elseif ($request->combo_id) {
            $combo = ProductCombo::find($request->combo_id);
            $query = ['type' => 'combo', 'data' => $combo];
        }

        if ($query['data']->quantity < $request->quantity) {
            dd($query['data']);
            return back()->with('error', 'Số lượng sản phẩm hiện tại không đáp ứng đủ');
        }

        if ($query['type'] === 'combo') {
            if ($query['data']->expired_at < now()) {
                return back()->with('error', 'Combo đã hết hạn');
            }
        }

        $result = null;

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->user_id = $userId;
            $cartItem->save();
            $result = $cartItem;
        } else {
            $result = Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'sku_id' => $request->sku_id,
                'combo_id' => $request->combo_id,
                'quantity' => $request->quantity,
                'item_type' => $itemType,
            ]);
        }

        if($request->checkout) {
            return redirect()->route('cart.index');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm vào giỏ hàng!',
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }
}
