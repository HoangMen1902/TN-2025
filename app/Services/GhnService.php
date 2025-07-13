<?php

namespace App\Services;

use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class GhnService
{
    protected $token;
    protected $apiToken;
    protected $orderToken;
    protected $shop_province_id;
    protected $shop_district_id;
    protected $shop_ward_id;

    public function __construct()
    {

        $this->apiToken = env('GHN_API');

        // $this->orderToken = env('GHN_TOKEN');

        $provinceNameFromConfig = config('shopConfig.shop_province');
        $districtNameFromConfig = config('shopConfig.shop_district');
        $wardNameFromConfig = config('shopConfig.shop_ward');

        $shop_province = collect($this->getProvinces() ?? [])
            ->first(function ($item) use ($provinceNameFromConfig) {
                return Str::contains(Str::lower($item['ProvinceName']), Str::lower($provinceNameFromConfig));
            });

        if (empty($shop_province)) {
            return;
        }
        $this->shop_province_id = $shop_province['ProvinceID'];

        $shop_district = collect($this->getDistricts($this->shop_province_id) ?? [])
            ->first(function ($item) use ($districtNameFromConfig) {
                return Str::contains(Str::lower($item['DistrictName']), Str::lower($districtNameFromConfig));
            });

        if (empty($shop_district)) {
            return;
        }
        $this->shop_district_id = $shop_district['DistrictID'];


        $shop_ward_id = collect($this->getWards($this->shop_district_id) ?? [])
            ->first(function ($item) use ($wardNameFromConfig) {
                return Str::contains(Str::lower($item['WardName']), Str::lower($wardNameFromConfig));
            });
        $this->shop_ward_id = $shop_ward_id['WardCode'];
    }

    public function getProvinces()
    {
        try {
            $response = Http::withHeaders(['Token' => $this->apiToken])
                ->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');

            if ($response->successful()) {
                $data = $response->json('data');
                return $data ?? [];
            } else {
                logger()->error('Error fetching provinces', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'url' => $response->effectiveUri()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching provinces', [
                'exception' => $e->getMessage(),
                'url' => 'https://online-gateway.ghn.vn/shiip/public-api/master-data/province'
            ]);
            return [];
        }
    }

    public function getDistricts($province_id)
    {
        try {
            $response = Http::withHeaders(['Token' => $this->apiToken])
                ->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                    'province_id' => (int) $province_id,
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            } else {
                logger()->error('Error fetching districts', ['response' => $response->body()]);
                return [];
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching districts', ['exception' => $e->getMessage()]);
            return [];
        }
    }

    public function getWards($district_id)
    {
        try {
            $response = Http::withHeaders(['Token' => $this->apiToken])
                ->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                    'district_id' => (int) $district_id,
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            } else {
                logger()->error('Error fetching wards', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'district_id' => $district_id
                ]);
                return [];
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching wards', [
                'exception' => $e->getMessage(),
                'district_id' => $district_id
            ]);
            return [];
        }
    }

    public function getServiceList($shop_district, $district)
    {
        try {
            $shop_id = (int)env('GHN_SHOPID');

            $response = Http::withHeaders([
                'token' => $this->apiToken
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
                "shop_id" => $shop_id,
                "from_district" => (int)$shop_district,
                "to_district" => (int)$district
            ]);
            if ($response->ok()) {
                return $response;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Logger('Error fetching districts', ['exception' => $th->getMessage()]);
        }
    }

    public function getEstimatedTime($district_id,  $ward_id)
    {
        try {
            $serviceTypeId = collect($this->getServiceList($this->shop_district_id, $district_id)['data'] ?? [])
                ->firstWhere('short_name', 'Hàng nhẹ')['service_type_id'] ?? null;
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->apiToken
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/leadtime', [
                "from_district_id" => (int)$this->shop_district_id,
                "from_ward_code" => $this->shop_ward_id,
                "to_district_id" => (int)$district_id,
                "to_ward_code" => $ward_id,
                "service_id" => (int)$serviceTypeId
            ]);
            return $response;
        } catch (\Throwable $th) {
            Logger('Lỗi khi tính thời gian ' . $th->getMessage());
            return false;
        }
    }


    /**
     * Summary of getFee
     * @param array $items
     * Mảng chứa Item thanh toán Chỉ sử dụng khi là hàng nặng
     * 
     * @param array $size
     * Mảng chứa chiều dài, rộng, cao của gói hàng VD:
     * $size = [
     * 'length' => 2,
     * 'width' => 3,
     * 'height => 4,
     * 'weight' => 500
     * ]
     * 
     * @param mixed $user_district
     * Quận/Huyện người nhận
     * @param mixed $user_ward
     * Phường/Xã người nhận
     * @throws \Exception
     * @return mixed
     */
    public function getFee(array $size, $user_district, $user_ward, array $items = null)
    {
        try {

            $serviceTypeId = collect($this->getServiceList($this->shop_district_id, 1935)['data'] ?? [])
                ->firstWhere('short_name', 'Hàng nhẹ')['service_type_id'] ?? null;
            if (!isset($serviceTypeId) || empty($serviceTypeId)) {
                throw new Exception('Not Found Service Type');
            }
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->apiToken
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', [
                "service_type_id" => $serviceTypeId,
                "from_ward_code" => $this->shop_ward_id,
                "to_district_id" => 1935,
                "to_ward_code" => "600401",
                "length" => $size['length'],
                "width" => $size['width'],
                "height" => $size['height'],
                "weight" => $size['weight'],
                "insurance_value" => 3,
                "coupon" => null,
            ]);
            if ($response->successful()) {
                return $response->body();
            } else {
                Log::error('Lỗi xảy ra khi lấy phí giao hàng' . $response->body());
                return [];
            }
        } catch (Exception $e) {
            Log::error('Lỗi khi tính phí: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo đơn hàng GHN từ Order đã được duyệt
     */
    public function createOrderFromOrder(\App\Models\Order $order)
    {
        try {
            Log::info('Start createOrderFromOrder', ['order_id' => $order->id]);

            if ($order->orderDetails->isEmpty()) {
                throw new \Exception('Đơn hàng không có sản phẩm');
            }

            $productTotal = 0;
            foreach ($order->orderDetails as $detail) {
                $productTotal += $detail->price * $detail->quantity;
            }

            $shippingFee = $order->shipment_price ?? 0;

            Log::info('Order pricing breakdown', [
                'product_total' => $productTotal,
                'shipping_fee' => $shippingFee,
                'order_total_price' => $order->total_price,
                'calculated_total' => $productTotal + $shippingFee
            ]);

            $addressInfo = [
                'to_name' => $order->customer_name,
                'to_phone' => $this->formatPhoneNumber($order->phone),
                'to_address' => $order->address,
                'to_ward_code' => '20308',
                'to_district_id' => 1444,
            ];

            $totalWeight = 0;
            $items = [];
            $content = [];

            foreach ($order->orderDetails as $detail) {
                if ($detail->item_type === 'sku' && $detail->sku) {
                    $product = $detail->sku->product;
                    $weight = $product->weight ?? 500;
                    $totalWeight += $weight * $detail->quantity;

                    $items[] = [
                        'name' => $product->name,
                        'quantity' => (int)$detail->quantity,
                        'price' => (int)$detail->price,
                        'weight' => (int)$weight
                    ];

                    $content[] = $product->name . ' x' . $detail->quantity;
                } elseif ($detail->item_type === 'combo' && $detail->combo) {
                    $weight = 500;
                    $totalWeight += $weight * $detail->quantity;

                    $items[] = [
                        'name' => $detail->combo->name,
                        'quantity' => (int)$detail->quantity,
                        'price' => (int)$detail->price,
                        'weight' => (int)$weight
                    ];

                    $content[] = $detail->combo->name . ' x' . $detail->quantity;
                }
            }

            $paymentMethod = $order->paymentDetail->payment_method ?? 'cod';
            $codAmount = 0;

            if ($paymentMethod === 'cod') {
                $maxCodLimit = 300000;
                $codAmount = min($productTotal, $maxCodLimit);

                Log::info('COD Payment - Thu tiền sản phẩm', [
                    'product_total' => $productTotal,
                    'shipping_fee' => $shippingFee,
                    'cod_amount' => $codAmount,
                    'is_limited' => $productTotal > $maxCodLimit,
                    'note' => 'COD chỉ thu tiền sản phẩm, phí ship GHN tự tính'
                ]);
            } else {
                $codAmount = 0;

                Log::info('Online Payment - Không thu COD', [
                    'payment_method' => $paymentMethod,
                    'product_total' => $productTotal,
                    'cod_amount' => 0
                ]);
            }

            Log::info('Order data prepared', [
                'payment_method' => $paymentMethod,
                'product_total' => $productTotal,
                'cod_amount' => $codAmount,
                'total_weight' => $totalWeight,
                'items_count' => count($items)
            ]);

            $note = 'Đơn hàng #' . $order->id;
            switch ($paymentMethod) {
                case 'cod':
                    if ($productTotal > 300000) {
                        $note .= ' - Thu COD 300k (SP: ' . number_format($productTotal) . 'đ + Ship riêng)';
                    } else {
                        $note .= ' - Thu COD ' . number_format($codAmount) . 'đ (chỉ tiền SP)';
                    }
                    break;
                case 'vnpay':
                    $note .= ' - Đã thanh toán VNPay (SP + Ship)';
                    break;
                case 'momo':
                    $note .= ' - Đã thanh toán MoMo (SP + Ship)';
                    break;
                case 'bank_transfer':
                    $note .= ' - Đã chuyển khoản (SP + Ship)';
                    break;
                case 'international':
                    $note .= ' - Đã thanh toán quốc tế (SP + Ship)';
                    break;
                default:
                    $note .= ' - Đã thanh toán online (SP + Ship)';
            }

            $orderData = [
                'payment_type_id' => $codAmount > 0 ? 2 : 1,
                'note' => $note,
                'required_note' => 'KHONGCHOXEMHANG',
                'client_order_code' => 'ORDER_' . $order->id,
                'to_name' => $addressInfo['to_name'],
                'to_phone' => $addressInfo['to_phone'],
                'to_address' => $addressInfo['to_address'],
                'to_ward_code' => $addressInfo['to_ward_code'],
                'to_district_id' => (int)$addressInfo['to_district_id'],
                'cod_amount' => (int)$codAmount,
                'content' => implode(', ', $content),
                'weight' => (int)max($totalWeight, 500),
                'length' => 30,
                'width' => 20,
                'height' => 10,
                'insurance_value' => (int)min($productTotal, 5000000), // Dùng productTotal
                'service_type_id' => 2,
                'items' => $items
            ];

            Log::info('Calling GHN API with data', $orderData);

            $response = $this->createOrder($orderData);

            if ($response && isset($response['data'])) {
                $order->update([
                    'shipping_order_code' => $response['data']['order_code'],
                    'shipping_status' => \App\Models\Order::SHIPPING_STATUS_DA_TAO_DON,
                    'shipping_info' => $response['data'],
                    'orders_status' => 'Vận chuyển'
                ]);

                Log::info('Order updated successfully', [
                    'order_id' => $order->id,
                    'shipping_code' => $response['data']['order_code']
                ]);

                return $response;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Lỗi tạo đơn GHN: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Format phone number
     */
    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[\s\-\.]/', '', $phone);

        if (strpos($phone, '+84') === 0) {
            $phone = '0' . substr($phone, 3);
        } elseif (strpos($phone, '84') === 0) {
            $phone = '0' . substr($phone, 2);
        }

        if (preg_match('/^0[3-9][0-9]{8}$/', $phone)) {
            return $phone;
        }

        return $phone;
    }

    /**
     * Lấy thông tin địa chỉ từ order
     */
    private function getAddressInfoFromOrder(\App\Models\Order $order)
    {
        try {
            $checkoutAddress = \App\Models\CheckoutAddress::where('user_id', $order->user_id)
                ->where('customer_name', $order->customer_name)
                ->where('phone', $order->phone)
                ->first();

            if ($checkoutAddress && $checkoutAddress->ward && $checkoutAddress->district) {
                $providerWard = \App\Models\providerWard::where('ward_id', $checkoutAddress->ward_id)
                    ->whereHas('provider', function ($q) {
                        $q->where('name', 'like', '%GHN%');
                    })
                    ->first();

                $providerDistrict = \App\Models\providerDistrict::where('district_id', $checkoutAddress->district_id)
                    ->whereHas('provider', function ($q) {
                        $q->where('name', 'like', '%GHN%');
                    })
                    ->first();

                if ($providerWard && $providerDistrict) {
                    return [
                        'to_name' => $order->customer_name,
                        'to_phone' => $order->phone,
                        'to_address' => $order->address,
                        'to_ward_code' => $providerWard->provider_ward_code,
                        'to_district_id' => (int)$providerDistrict->provider_district_code,
                    ];
                }
            }

            return [
                'to_name' => $order->customer_name,
                'to_phone' => $order->phone,
                'to_address' => $order->address,
                'to_ward_code' => '20308',
                'to_district_id' => 1444,
            ];
        } catch (\Exception $e) {
            Log::error('Lỗi lấy thông tin địa chỉ: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo đơn hàng trên GHN API
     */

    public function createOrder(array $orderData)
    {
        try {
            Log::info('GHN Create Order - Token Debug', [
                'api_token' => $this->apiToken ? substr($this->apiToken, 0, 10) . '...' : 'NULL',
                'order_token' => $this->orderToken ? substr($this->orderToken, 0, 10) . '...' : 'NULL',
                'shop_id' => env('GHN_SHOPID'),
                'token_being_used' => $this->orderToken ?? $this->token ?? 'UNDEFINED'
            ]);
            $baseUrl = env('GHN_API_URL', 'https://online-gateway.ghn.vn/shiip/public-api');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->apiToken ?? $this->token
            ])->post($baseUrl . '/v2/shipping-order/create', array_merge($orderData, [
                'from_ward_code' => $this->shop_ward_id,
                'from_district_id' => $this->shop_district_id,
            ]));

            Log::info('GHN API Response Details', [
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'successful' => $response->successful(),
                'json_response' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                Log::info('GHN Create Order Success', [
                    'response_data' => $responseData
                ]);

                return $responseData;
            } else {
                Log::error('GHN Create Order Failed', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                    'response_json' => $response->json(),
                    'request_data' => $orderData,
                    'headers_sent' => [
                        'ShopId' => (int)env('GHN_SHOPID'),
                        'Token' => substr($this->orderToken ?? $this->token, 0, 10) . '...'
                    ]
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GHN Create Order Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Tracking đơn hàng
     */
    public function trackOrder($orderCode)
    {
        try {
            // SỬA: Dùng orderToken giống như createOrder
            $response = Http::withHeaders([
                'Token' => $this->orderToken, // Dùng lại orderToken
                'ShopId' => (int)env('GHN_SHOPID'), // Thêm ShopId như createOrder
                'Content-Type' => 'application/json'
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
                'order_code' => $orderCode
            ]);

            Log::info('GHN Tracking API Call (Using orderToken like createOrder)', [
                'order_code' => $orderCode,
                'token_used' => substr($this->orderToken, 0, 10) . '...',
                'shop_id' => env('GHN_SHOPID'),
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Lỗi tracking đơn hàng GHN', [
                    'order_code' => $orderCode,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi tracking đơn hàng GHN: ' . $e->getMessage());
            return false;
        }
    }
    public function getStatusText($status)
    {
        $statusMap = [
            'ready_to_pick' => 'Chờ lấy hàng',
            'picking' => 'Đang lấy hàng',
            'picked' => 'Đã lấy hàng',
            'storing' => 'Đang lưu kho',
            'transporting' => 'Đang vận chuyển',
            'sorting' => 'Đang phân loại',
            'delivering' => 'Đang giao hàng',
            'delivered' => 'Đã giao thành công',
            'delivery_fail' => 'Giao hàng thất bại',
            'waiting_to_return' => 'Chờ trả hàng',
            'return' => 'Đang trả hàng',
            'returned' => 'Đã trả hàng',
            'exception' => 'Có sự cố',
            'damage' => 'Hàng bị hỏng',
            'lost' => 'Thất lạc'
        ];

        return $statusMap[$status] ?? $status;
    }
    public function testConnection()
    {
        try {
            $provinces = $this->getProvinces();
            if (!empty($provinces)) {
                return [
                    'status' => 'success',
                    'message' => 'Kết nối GHN API thành công!',
                    'shop_info' => [
                        'province_id' => $this->shop_province_id ?? 'not_set',
                        'district_id' => $this->shop_district_id ?? 'not_set',
                        'ward_id' => $this->shop_ward_id ?? 'not_set',
                    ],
                    'provinces_count' => count($provinces),
                    'token' => substr($this->orderToken, 0, 10) . '...' // Hiển thị 10 ký tự đầu
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Không thể lấy dữ liệu từ GHN API'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi kết nối: ' . $e->getMessage()
            ];
        }
    }
}
