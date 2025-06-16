<?php

namespace App\Services;

use PayOS\PayOS;

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

    public function createPaymentLink($order, $returnUrl, $webhookUrl)
    {
        $data = [
            'orderCode' => $order->id,
            'amount' => (int) $order->total_price,
            'description' => 'Thanh toán đơn hàng #' . $order->id,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $returnUrl,
            'webhookUrl' => $webhookUrl,
        ];

        return $this->payos->createPaymentLink($data);
    }
    public function verifyWebhook(array $payload): bool
    {
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $receivedChecksum = $payload['checksum'] ?? null;

        if (!$receivedChecksum) {
            return false;
        }

        // Loại bỏ checksum khỏi dữ liệu để tính lại
        unset($payload['checksum']);

        // Sắp xếp thứ tự alphabet theo key
        ksort($payload);

        // Ghép các giá trị lại thành chuỗi
        $dataToSign = implode('', array_values($payload));

        // Tính lại checksum
        $calculatedChecksum = hash_hmac('sha256', $dataToSign, $checksumKey);

        return $calculatedChecksum === $receivedChecksum;
    }
}
