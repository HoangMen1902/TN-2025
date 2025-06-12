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
use App\Services\Stripe;
use App\Services\StripeService;
use Illuminate\Support\Facades\Crypt;

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

            $order = Order::create([
                'orders_status' => $request->payment_method === "cod" ? 'Đang xử lý' : 'Chờ thanh toán',
                'user_id' => $user->id,
                'address' => $fullAddress ?? $request->full_address,
                'phone' => $addressModel?->phone ?? $request->phone,
                'contact_email' => $addressModel?->user->email ?? $request->contact_email,
                'customer_name' => $addressModel?->customer_name ?? $request->customer_name,
                'total_price' => $totalPrice,
                'shipment_price' => $shipment_fee
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'price' => $item->sku->sale_price,
                    'quantity' => $item->quantity,
                ]);
            }

            do {
                $trackingId = Str::uuid();
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
                $voucher = session('voucher');
                $stripeService = new StripeService;
                $order_id = $payment->order_id;
                $session = $stripeService->createCheckoutSession($cartItems, $shipment_fee, $order_id, isset($voucher) && !empty($voucher) ? $voucher : null);
                return redirect($session->url);
            }

            return redirect()->route('thanks', ['payment_id' => $payment->id])->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
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

                    $order->orders_status = 'Đã thanh toán';
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

    public function internationalCallback($checkout_id, $payment_id) {
        $stripeService = new StripeService();
        $payment = PaymentDetail::find($payment_id);
        
        if(!$stripeService->checkCheckoutId($checkout_id)|| !$payment || $payment->order->user_id !== Auth::id()) {
            return redirect()->route(route('home'))->with('error','Đường dẫn không hợp lệ');
        }
        $stripe_payment_id = $stripeService->getChargeId($checkout_id);
        if(!$payment_id) {
            return redirect(route('home'))->with('error', 'Có lỗi khi truy cập trang');
        }
        $payment->payment_id = $stripe_payment_id;
        $payment->save();
        return redirect(route('thanks', ['payment_id' => $payment_id,]))->with('success','Đã đặt hàng thành công');
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
        if(!$payment) {
            return redirect(route('home'))->with('error', 'Không hợp lệ');
        }
        if($payment->order->user_id !== Auth::id()) {
            return redirect(route('home'))->with('error', 'Không hợp lệ');
        }

        return view('paymentmodule::components.thanks', ['payment' => $payment]);
    }
}
