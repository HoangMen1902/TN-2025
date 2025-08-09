<?php

namespace Modules\PaymentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\VnPay;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CheckoutAddress;
use App\Models\FlashsaleProduct;
use App\Models\Voucher;
use App\Models\VoucherUsed;
use App\Services\Stripe;
use App\Services\StripeService;
use Illuminate\Support\Facades\Crypt;
use App\Services\PayOsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PaymentModuleController extends Controller
{
    public function index()
    {
        return redirect('/gio-hang');
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'selected_address' => 'required|exists:checkout_addresses,id',
            'cart_id' => 'required|array',
            'payment_method' => 'required|string',
            'shipment_unit' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {

            $user = Auth::user();

            $cartItems = Cart::with('sku')->whereIn('id', $request->cart_id)->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng không hợp lệ.');
            }

            $totalPrice = session('order_total');
            $shipment_fee = session('shipping_fee');
            session()->forget('order_total');
            session()->forget('shipping_fee');

            $selectedAddressId = $request->selected_address;
            $addressModel = CheckoutAddress::with(['ward', 'district', 'province', 'user'])
                ->where('user_id', $user->id)
                ->find($selectedAddressId);
            $fullAddress = null;
            if ($addressModel) {

                $fullAddress = $addressModel->address;
                if ($addressModel->ward?->name) {
                    $fullAddress .= ', ' . $addressModel->ward->name;
                }
                if ($addressModel->district?->name) {
                    $fullAddress .= ', ' . $addressModel->district->name;
                }
                if ($addressModel->province?->name) {
                    $fullAddress .= ', ' . $addressModel->province->name;
                }
            }
            $voucher_code = $request->voucher_code;

            $voucher = Voucher::where('voucher_code', '=', $voucher_code)->where('start_at', '<', now())->where('expired_at', '>', now())->where('voucher_status', 'active')->first();
            $userVoucherCheck = null;
            if ($voucher) {
                $user_voucher = $voucher->voucherUSed->first();
                $userVoucherCheck = $user_voucher->user_id === Auth::id() && $voucher->voucherUsed->first()->is_used === false ? $voucher->id : null;
            }

            $decrease_amount = Session::get('decrease_amount', 0);
            Session::forget('decrease_amount');

            $order = Order::create([
                'orders_status' => $request->payment_method === "cod" || $request->payment_method === "payos" ? 'Chờ duyệt' : 'Chờ thanh toán',
                'user_id' => $user->id,
                'address' => $fullAddress ?? $request->full_address,
                'phone' => $addressModel?->phone ?? $request->phone,
                'contact_email' => $addressModel?->user->email ?? $request->contact_email,
                'customer_name' => $addressModel?->customer_name ?? $request->customer_name,
                'total_price' => $totalPrice,
                'shipment_price' => $shipment_fee,
                'is_paid' => false,
                'amount_decrease' => $decrease_amount,
                'province_id' => $addressModel->province_id,
                'district_id' => $addressModel->district_id,
                'ward_id' => $addressModel->ward_id,
                'decrease_amount' => $decrease_amount,
                'voucher_id' => $userVoucherCheck,
            ]);

            if ($voucher) {
                $user_voucher->is_used = true;
                $user_voucher->save();
            }

            // Gửi thông báo đặt hàng thành công cho user
            \App\Services\NotificationService::send(
                [
                    $user->id
                ],
                'Đặt hàng thành công',
                'Bạn vừa đặt đơn hàng #' . $order->id . '. Trạng thái: ' . $order->orders_status,
                'Đơn hàng'
            );

            $total_price = 0;

            foreach ($cartItems as $item) {
                $price = 0;
                $comboId = null;
                if ($item->item_type === "sku") {
                    $price = $item->sku->sale_price ?? $item->sku->price;
                    $flashsaleData = $this->checkFlashsale($item->sku_id);
                    if ($flashsaleData && !empty($flashsaleData)) {

                        $discount_type = $flashsaleData[$item->sku_id]['discount_type'];
                        $discount_amount = $flashsaleData[$item->sku_id]['discount_amount'];

                        if ($discount_type === 'percent') {
                            $price -= ($price * $discount_amount / 100);
                        } elseif ($discount_type === 'specific') {
                            $price -= $discount_amount;
                        }

                        if ($price < 0) {
                            $price = 0;
                        }
                    }
                    $total_price += $price * $item->quantity;
                } elseif ($item->item_type === 'combo') {
                    $price = $item->combo->sale_price;
                    $comboId = $item->combo_id;
                    $total_price += $price * $item->quantity;
                }
                OrderDetail::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'item_type' => $item->item_type,
                    'combo_id' => $comboId,
                    'total_price' => $total_price
                ]);
            }

            do {
                $trackingId =   strtoupper(Str::random(8));
            } while (PaymentDetail::where('tracking_id', $trackingId)->exists());

            $paymentMethod = $request->payment_method;

            $payment = PaymentDetail::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'payment_id' => null,
                'tracking_id' => $trackingId,
                'shipment_unit' => $request->shipment_unit,
            ]);

            Cart::whereIn('id', $request->cart_id)->delete();

            DB::commit();

            session()->forget('shipping_address');
            session()->forget('selected_address_id');
            if ($paymentMethod === 'vnpay') {
                $vnPayService = new VnPay();
                $vnPay = $vnPayService->vnpayPayment($order, $totalPrice, $request->ip());
                return redirect($vnPay);
            } elseif ($paymentMethod === "international") {
                $stripeService = new StripeService;
                $order_id = $payment->order_id;
                $session = $stripeService->createCheckoutSession($cartItems, $shipment_fee, $payment->id);
                $payment->payment_url = $session->url;
                $payment->payment_expired_at = now()->addMinutes(10);
                $payment->save();
                return redirect($session->url);
            } elseif ($paymentMethod === 'payos') {
                $payosService = new PayOsService();
                $payosResponse = $payosService->createPaymentLink(
                    $order,
                    route('thanks', ['payment_id' => $payment->id]),
                    route('cart.index')
                );
                return redirect($payosResponse['checkoutUrl']);
            }



            return redirect()->route('thanks', ['payment_id' => $payment->id])->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return redirect('/gio-hang')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    private function checkFlashsale($skuId)
    {


        $now = Carbon::now();
        $flashsales = FlashsaleProduct::where('sku_id', $skuId)
            ->whereHas('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })
            ->with('flashsale')
            ->get();

        $flashsaleMap = $flashsales->mapWithKeys(function ($item) {
            dd($item->flashsale);

            return [
                "$item->sku_id" => [
                    'discount_type' => $item->flashsale->discount_type,
                    'discount_amount' => $item->flashsale->discount_amount
                ]
            ];
        })->toArray();

        return $flashsaleMap;
    }
    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = $request->all();

        Log::info('VNPAY callback dữ liệu nhận được:', $inputData);

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;

        $dataToHash = $inputData;
        unset($dataToHash['vnp_SecureHash']);
        unset($dataToHash['vnp_SecureHashType']);

        ksort($dataToHash);

        $dataToHash = array_filter($dataToHash, function ($value) {
            return $value !== null && $value !== '';
        });

        $hashDataArray = [];
        foreach ($dataToHash as $key => $value) {
            if ($value !== null && $value !== '') {
                $hashDataArray[] = $key . '=' . urlencode($value);
            }
        }
        $hashData = implode('&', $hashDataArray);

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        Log::info('Hash comparison:', [
            'calculated_hash' => $secureHash,
            'received_hash' => $vnp_SecureHash,
            'hash_data' => $hashData,
            'hash_secret_length' => strlen($vnp_HashSecret),
            'data_to_hash' => $dataToHash
        ]);

        if ($secureHash === $vnp_SecureHash) {
            $orderId = $request->input('vnp_TxnRef');
            $responseCode = $request->input('vnp_ResponseCode');

            $order = Order::find($orderId);

            if ($order) {
                if ($responseCode === '00') {
                    $paymentDetail = PaymentDetail::where('order_id', $orderId)->first();
                    if ($paymentDetail) {
                        $paymentDetail->update([
                            'payment_id' => $request->input('vnp_TransactionNo')
                        ]);
                    }

                    $order->orders_status = 'Chờ duyệt';
                    $order->save();

                    // Log::info('Thanh toán VNPAY thành công cho đơn hàng:', ['order_id' => $orderId]);
                    return redirect()->route('thanks', ['payment_id' => $paymentDetail->id])->with('success', 'Thanh toán thành công!');
                } else {
                    $order->orders_status = 'Thanh toán thất bại';
                    $order->save();

                    Log::warning('Thanh toán VNPAY thất bại:', [
                        'order_id' => $orderId,
                        'response_code' => $responseCode,
                        'message' => $this->getVNPayResponseMessage($responseCode)
                    ]);

                    return redirect()->route('home')->with('error', 'Thanh toán thất bại: ' . $this->getVNPayResponseMessage($responseCode));
                }
            } else {
                Log::error('Không tìm thấy đơn hàng khi callback VNPAY:', ['order_id' => $orderId]);
                return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
            }
        } else {
            Log::error('Chữ ký VNPAY không hợp lệ.', [
                'calculated_hash' => $secureHash,
                'received_hash' => $vnp_SecureHash,
                'hash_data' => $hashData
            ]);
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ.');
        }
    }

    private function getVNPayResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP).',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định.',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    public function internationalCallback($checkout_id, $payment_id)
    {
        $stripeService = new StripeService();
        $payment = PaymentDetail::find($payment_id);

        if (!$stripeService->checkCheckoutId($checkout_id) || !$payment || $payment->order->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'Đường dẫn không hợp lệ');
        }
        $stripe_payment_id = $stripeService->getChargeId($checkout_id);
        if (!$payment_id) {
            return redirect(route('home'))->with('error', 'Có lỗi khi truy cập trang');
        }
        $payment->payment_id = $stripe_payment_id;
        $payment->order->is_paid = true;
        $payment->order->orders_status = 'Chờ duyệt';
        $payment->save();
        $payment->order->save();
        return redirect(route('thanks', ['payment_id' => $payment_id,]))->with('success', 'Đã đặt hàng thành công');
    }
    public function show($id)
    {
        return view('paymentmodule::show');
    }

    public function edit($id)
    {
        return view('paymentmodule::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function thanks($payment_id)
    {
        $payment = PaymentDetail::find($payment_id);
        if (!$payment) {
            return redirect(route('home'))->with('error', 'Không hợp lệ');
        }
        if ($payment->order->user_id !== Auth::id()) {
            return redirect(route('home'))->with('error', 'Không hợp lệ');
        }

        return view('paymentmodule::components.thanks', ['payment' => $payment]);
    }
    public function payosWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Webhook nhận từ PayOS:', $payload);

            $payosService = new PayOsService();

            if (!$payosService->verifyWebhook($payload)) {
                Log::warning('Webhook PayOS: Chữ ký không hợp lệ.');
                return response()->json(['message' => 'unauthorized'], 401);
            }

            $data = $payload['data'] ?? [];
            $orderCode = $data['orderCode'] ?? null;

            // Bỏ qua webhook test khi đổi URL
            if ((int) $orderCode === 123) {
                Log::info("Nhận webhook test từ PayOS khi đổi URL, bỏ qua xử lý đơn hàng.");
                return response()->json(['message' => 'OK']);
            }

            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                Log::error(" Không tìm thấy đơn hàng PayOS: $orderCode");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Kiểm tra trạng thái thanh toán
            $paymentSuccess = ($data['code'] ?? null) === "00";

            if ($paymentSuccess) {
                $order->orders_status = 'Đã thanh toán';
                $order->is_paid = true;
                $order->save();

                PaymentDetail::where('order_id', $order->id)->update([
                    'payment_id' => $data['reference'] ?? null,
                ]);

                Log::info("Đơn hàng {$order->id} đã được thanh toán thành công.");
            } else {
                $order->orders_status = 'Đã hủy';
                $order->save();
                Log::info("Đơn hàng {$order->id} đã bị hủy.");
            }

            return response()->json(['message' => 'OK']);
        } catch (\Throwable $e) {
            Log::error("Lỗi xử lý webhook PayOS: " . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Trả về 200 để PayOS không retry, nhưng vẫn log lỗi
            return response()->json(['message' => 'internal error'], 200);
        }
    }
}
