<?php

namespace Modules\PaymentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentDetail;
use App\Models\ProductSku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect('/gio-hang');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function paymentPage(Request $request)
    {
        $cartIds = $request->input('cart_id');

        $carts = Cart::whereIn('id', $cartIds)->get();
        return view('paymentmodule::index', ['carts' => $carts]);
    }
    public function create()
    {
        return view('paymentmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|array',
            'shipment_unit' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/',
            'contact_email' => 'nullable|email',
            'customer_name' => 'required|string',
            'full_address' => 'nullable|string',  
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();

            Log::info('Địa chỉ full_address từ request:', ['full_address' => $request->full_address]);

            $cartItems = Cart::with('sku')->whereIn('id', $request->cart_id)->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng không hợp lệ.');
            }

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->quantity * $item->sku->price;
            });

            $order = Order::create([
                'orders_status' => 'Đang xử lý',
                'user_id' => $user->id,
                'address' => session('shipping_address.full_address'),
                'phone' => $request->phone,
                'contact_email' => $request->contact_email,
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
            ]);

            Log::info('Đơn hàng đã tạo:', ['order_id' => $order->id, 'address' => $order->address]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'price' => $item->sku->price,
                    'quantity' => $item->quantity,
                ]);
            }

            do {
                $trackingId = strtoupper(Str::random(10));
            } while (PaymentDetail::where('tracking_id', $trackingId)->exists());

            PaymentDetail::create([
                'order_id' => $order->id,
                'payment_method' => 'cod',
                'payment_id' => null,
                'tracking_id' => $trackingId,
                'shipment_unit' => $request->shipment_unit,
            ]);

            Cart::whereIn('id', $request->cart_id)->delete();

            DB::commit();

            session()->forget('shipping_address');  

            return redirect()->route('orders.success')->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

         
            Log::error('Lỗi khi tạo đơn hàng:', ['message' => $e->getMessage(), 'line' => $e->getLine()]);

            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('paymentmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
