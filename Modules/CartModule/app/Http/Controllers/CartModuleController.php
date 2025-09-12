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
use Illuminate\Support\Facades\Session;

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

        $cartItem = null;
        $existed_cart = false;

        if (Auth::check()) {

            $cartItem = Cart::where('user_id', $userId)
                ->where('item_type', $itemType)
                ->where($itemIdField, $itemId)
                ->first();
        } else {
            $carts = Session::get('carts', []);
            if (isset($carts[$itemType][$itemId])) {
                $existed_cart = true;
            }
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
            return back()->with('error', 'Số lượng sản phẩm hiện tại không đáp ứng đủ');
        }

        if ($query['type'] === 'combo') {
            if ($query['data']->expired_at < now()) {
                return back()->with('error', 'Combo đã hết hạn');
            }
        }



        if ($cartItem) {
            $data = $sku ?? $combo;
            if ($cartItem->quantity + $request->quantity > $data->quantity) {
                return back()->with('error', 'Số lượng sản phẩm hiện tại không đáp ứng đủ');
            }
            $cartItem->quantity += $request->quantity;
            $cartItem->user_id = $userId;
            $cartItem->save();
        } elseif ($existed_cart) {
            $data = $sku ?? $combo;

            if ($carts[$itemType][$itemId]['quantity'] + $request->quantity > $data->quantity) {
                return back()->with('error', 'Số lượng sản phẩm hiện tại không đáp ứng đủ');
            }
            $carts[$itemType][$itemId]['quantity'] += $request->quantity;
            Session::put('carts', $carts);
        } elseif (Auth::check() && !$cartItem) {
            $data = $sku ?? $combo;

            if ($request->quantity > $data->quantity) {
                return back()->with('error', 'Số lượng sản phẩm hiện tại không đáp ứng đủ');
            }
            $result = Cart::create([
                'user_id' => $userId,
                'sku_id' => $request->sku_id,
                'combo_id' => $request->combo_id,
                'quantity' => $request->quantity,
                'item_type' => $itemType,
            ]);
        } else {
            $carts[$itemType][$itemId] = [
                'quantity' => $request->quantity,
            ];
            Session::put('carts', $carts);
        }

        if ($request->checkout) {
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
