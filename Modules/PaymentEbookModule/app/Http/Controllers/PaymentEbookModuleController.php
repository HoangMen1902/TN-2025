<?php

namespace Modules\PaymentEbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EbookOrder;
use App\Models\EbookOrderDetail;
use App\Models\EbookPaymentDetail;
use App\Models\ProductEbook;
use App\Services\VnPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\StripeService;
use App\Services\PayOsService;

class PaymentEbookModuleController extends Controller
{
    public function index(Request $request)
    {
        $ebookId = $request->input('ebook_id');
        $ebook = ProductEbook::find($ebookId);
        if (!$ebook) {
            return redirect()->back()->with('error', 'Ebook không tồn tại.');
        }
        return view('paymentebookmodule::index', ['ebook' => $ebook]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:product_ebooks,id',
            'payment_method' => 'required|string|in:vnpay,international,payos',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $ebook = ProductEbook::find($request->ebook_id);
            if (!$ebook) {
                return back()->with('error', 'Ebook không hợp lệ.');
            }

            $totalPrice = $ebook->price; // Giá của 1 ebook

            $order = EbookOrder::create([
                'orders_status' => 'Chờ thanh toán',
                'user_id' => $user->id,
                'total_price' => $totalPrice,
            ]);

            EbookOrderDetail::create([
                'ebook_order_id' => $order->id,
                'ebook_id' => $ebook->id,
                'price' => $ebook->price,
                'quantity' => 1, 
                'total_price' => $ebook->price,
            ]);

            do {
                $trackingId = Str::uuid();
            } while (EbookPaymentDetail::where('tracking_id', $trackingId)->exists());

            $payment = EbookPaymentDetail::create([
                'ebook_order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'tracking_id' => $trackingId,
            ]);

            DB::commit();

            if ($request->payment_method === 'vnpay') {
                $vnPayService = new VnPay();
                $vnPay = $vnPayService->vnpayPayment($order, $totalPrice, $request->ip());
                return redirect($vnPay);
            } elseif ($request->payment_method === 'international') {
                $stripeService = new StripeService();
                // Chuyển đối tượng ebook thành Collection để khớp với StripeService
                $items = collect([$ebook]);
                $session = $stripeService->createCheckoutSession($items, 0, $order->id);
                if (!$session) {
                    throw new \Exception('Không thể tạo session thanh toán với Stripe.');
                }
                return redirect($session->url);
            } elseif ($request->payment_method === 'payos') {
                $payosService = new PayOsService();
                $payosResponse = $payosService->createPaymentLink(
                    $order,
                    route('ebook.thanks', ['payment_id' => $payment->id]),
                    route('ebook.payos.webhook')
                );
                if (isset($payosResponse['checkoutUrl'])) {
                    return redirect($payosResponse['checkoutUrl']);
                } else {
                    throw new \Exception('Không thể tạo liên kết thanh toán với PayOS.');
                }
            }

            return redirect()->route('ebook.thanks', ['payment_id' => $payment->id])->with('success', 'Đặt hàng ebook thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function thanks($payment_id)
    {
        $payment = EbookPaymentDetail::find($payment_id);
        if (!$payment || $payment->order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Không hợp lệ');
        }
        return view('paymentebookmodule::ebook-thanks', ['payment' => $payment]);
    }

    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;

        $dataToHash = $inputData;
        unset($dataToHash['vnp_SecureHash']);
        unset($dataToHash['vnp_SecureHashType']);
        ksort($dataToHash);
        $hashData = http_build_query($dataToHash);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $orderId = $request->input('vnp_TxnRef');
            $responseCode = $request->input('vnp_ResponseCode');

            $order = EbookOrder::find($orderId);
            if ($order) {
                if ($responseCode === '00') {
                    $payment = EbookPaymentDetail::where('', $orderId)->first();
                    if ($payment) {
                        $payment->update(['payment_id' => $request->input('vnp_TransactionNo')]);
                        $order->orders_status = 'Đã thanh toán';
                        $order->save();
                        return redirect()->route('ebook.thanks', ['payment_id' => $payment->id])->with('success', 'Thanh toán thành công!');
                    }
                } else {
                    $order->orders_status = 'Thanh toán thất bại';
                    $order->save();
                    return redirect()->route('home')->with('error', 'Thanh toán thất bại.');
                }
            }
        }
        return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ.');
    }

    public function internationalCallback($checkout_id, $payment_id)
    {
        $stripeService = new StripeService();
        $payment = EbookPaymentDetail::find($payment_id);
        if (!$payment || $payment->order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Đường dẫn không hợp lệ hoặc không được phép.');
        }

        if ($stripeService->checkCheckoutId($checkout_id)) {
            $stripe_payment_id = $stripeService->getChargeId($checkout_id);
            $payment->payment_id = $stripe_payment_id;
            $payment->save();

            $order = $payment->order;
            if ($order) {
                $order->orders_status = 'Đã thanh toán';
                $order->save();
            }
            return redirect()->route('ebook.thanks', ['payment_id' => $payment_id])->with('success', 'Đã đặt hàng thành công');
        }

        return redirect()->route('home')->with('error', 'Thanh toán không thành công.');
    }

    public function payosWebhook(Request $request)
    {
        $payload = $request->all();
        $payosService = new PayOsService();
        if (!$payosService->verifyWebhook($payload)) {
            return response()->json(['message' => 'unauthorized'], 401);
        }

        $orderId = $payload['orderCode'];
        $status = $payload['status'];
        $order = EbookOrder::find($orderId);

        if ($order) {
            if ($status == 1) {
                $order->orders_status = 'Đã thanh toán';
                $order->save();
                EbookPaymentDetail::where('ebook_order_id', $orderId)->update(['payment_id' => $payload['transactionId']]);
            } else {
                $order->orders_status = 'Thanh toán thất bại';
                $order->save();
            }
        }

        return response()->json(['message' => 'OK']);
    }
}