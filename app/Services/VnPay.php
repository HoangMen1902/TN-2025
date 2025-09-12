<?php

namespace App\Services;

class VnPay
{
    private $hash;
    private $tmn_code;
    public function __construct()
    {
        $this->tmn_code = env('VNP_TMN_CODE');
        $this->hash = env('VNP_HASH_SECRET');
    }


    public function vnpayPayment($order, $order_total, $user_ip): string
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.callback');
        $vnp_TmnCode = $this->tmn_code;
        $vnp_HashSecret = $this->hash;
        $vnp_TxnRef = $order->id;
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $user_ip;
        $vnp_Amount = (int) round($order_total * 100);
        // Log::info('Tổng tiền thanh toán:', [
        //     'total_price' => $totalPrice,
        //     'vnp_Amount' => $vnp_Amount,
        //     'order_id' => $order->id
        // ]);

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);

        $inputData = array_filter($inputData, function ($value) {
            return $value !== null && $value !== '';
        });

        $hashDataArray = [];
        foreach ($inputData as $key => $value) {
            if ($value !== null && $value !== '') {
                $hashDataArray[] = $key . '=' . urlencode($value);
            }
        }
        $hashData = implode('&', $hashDataArray);

        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Log::info('VNPAY Payment Hash Data:', [
        //     'hash_data' => $hashData,
        //     'secure_hash' => $vnp_SecureHash,
        //     'input_data' => $inputData
        // ]);

        $query = http_build_query($inputData);
        $vnp_Url .= "?" . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return $vnp_Url;
    }
}
