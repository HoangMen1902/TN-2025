<?php

namespace App\Services;

use PayOS\PayOS;
use Illuminate\Support\Facades\Log;

class PayOsService
{
    protected $payos;

    public function __construct()
    {
        $this->payos = new PayOS(
            clientId: env('PAYOS_CLIENT_ID'),
            apiKey: env('PAYOS_API_KEY'),
            checksumKey: env('PAYOS_CHECKSUM_KEY')
        );
    }

    public function createPaymentLink($order, $returnUrl, $cancelUrl)
    {
        $orderCode = (int) ($order->id . substr(now()->timestamp, -4));
        $order->order_code = $orderCode;
        $order->save();

        $data = [
            'orderCode' => $orderCode,
            'amount' => (int) $order->total_price,
            'description' => 'Thanh toán đơn hàng #' . $order->id,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
        ];

        return $this->payos->createPaymentLink($data);
    }

 public function verifyWebhook(array $payload): bool
{
    try {
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        $receivedSignature = $payload['signature'] ?? null;

        if (!$receivedSignature || !isset($payload['data'])) {
            return false;
        }

        $transaction = $payload['data'];

        if (!is_array($transaction)) {
            Log::warning('Dữ liệu transaction không hợp lệ:', [$transaction]);
            return false;
        }

        ksort($transaction);

        $transaction_str_arr = [];

        foreach ($transaction as $key => $value) {
            if (in_array($value, ["undefined", "null"]) || is_null($value)) {
                $value = '';
            }

            if (is_array($value)) {
                array_walk($value, function (&$element) {
                    if (is_array($element)) {
                        ksort($element);
                    }
                });
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            $transaction_str_arr[] = $key . '=' . $value;
        }

        $transaction_str = implode('&', $transaction_str_arr);
        Log::info('Chuỗi cần ký:', [$transaction_str]);

        $calculatedSignature = hash_hmac('sha256', $transaction_str, $checksumKey);
        Log::info('Checksum tính toán:', [$calculatedSignature]);
        Log::info('Signature từ PayOS:', [$receivedSignature]);

        return hash_equals($calculatedSignature, $receivedSignature);

    } catch (\Throwable $e) {
        Log::error("Lỗi verifyWebhook PayOS: " . $e->getMessage(), [
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);

        return false; // Nếu lỗi thì coi như không hợp lệ
    }
}



}
