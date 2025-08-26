<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VietQRService
{
    protected string $baseUrl = 'https://api.vietqr.io/v2/';
    protected ?string $clientId;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->clientId = env('VIETQR_CLIENT_ID');
        $this->apiKey   = env('VIETQR_API_KEY');
    }

    /**
     * Gọi API VietQR để tạo QR Code
     */
    public function generateQRCode(array $data): array
    {
        $response = Http::withHeaders([
            'x-client-id'  => $this->clientId,
            'x-api-key'    => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'generate', $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('VietQR API Error: ' . $response->body());
    }

    /**
     * Lấy danh sách ngân hàng từ VietQR
     */
    public function getBanks(): array
    {
        $response = Http::get($this->baseUrl . 'banks');

        if ($response->successful()) {
            $json = $response->json();
            if (($json['code'] ?? '') === '00' && isset($json['data'])) {
                return $json['data'];
            }
            throw new \Exception('VietQR API responded with error: ' . ($json['desc'] ?? 'Unknown error'));
        }

        throw new \Exception('Error calling VietQR banks API: ' . $response->body());
    }
    
}
