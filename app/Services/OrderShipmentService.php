<?php
// File: app/Services/OrderShipmentService.php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\providerProvinces;
use App\Models\providerDistrict;
use App\Models\providerWard;
use App\Models\Provider;

class OrderShipmentService
{


    public static function processReturnShipment(Order $order, $shipmentUnit)
    {
        Log::info('Bắt đầu đăng đơn trả hàng (lấy hàng từ khách)', [
            'order_id' => $order->id,
            'shipment_unit' => $shipmentUnit,
        ]);

        $switchValue = strtolower($shipmentUnit ?? '');

        switch ($switchValue) {
            case 'giao hàng nhanh':
            case 'giao_hang_nhanh':
            case 'ghn':
                Log::info('Chọn GHN trả hàng');
                $result = self::processGHNReturnShipment($order);
                break;

            case 'viettel post':
            case 'viettel_post':
            case 'viettelpost':
                Log::info('Chọn Viettel Post trả hàng');
                $result = self::processViettelPostReturnShipment($order);
                break;

            default:
                Log::warning('Đơn vị vận chuyển không hỗ trợ trả hàng, dùng GHN mặc định');
                $result = self::processGHNReturnShipment($order);
        }

        self::handleShipmentResult($order, $result, 'Trả hàng');
    }
    // GHN: Tạo đơn trả hàng (from: khách, to: shop)
    private static function processGHNReturnShipment(Order $order)
    {
        $ghnService = new \App\Services\GhnService();

        
        $fromAddress = [
            'from_name' => $order->customer_name,
            'from_phone' => $order->phone,
            'from_address' => $order->address,
            'from_ward_code' => $order->ward_id,
            'from_district_id' => $order->district_id,
        ];

        
        $toAddress = [
            'to_name' => config('shopConfig.shop_name'),
            'to_phone' => config('shopConfig.shop_phone'),
            'to_address' => config('shopConfig.shop_address'),
            'to_ward_code' => $ghnService->shop_ward_id,
            'to_district_id' => $ghnService->shop_district_id,
        ];
        $items = [];
        foreach ($order->orderDetails as $detail) {
            $items[] = [
                'name' => $detail->product_name ?? 'Sản phẩm',
                'quantity' => (int) ($detail->quantity ?? 1),
                'price' => (int) ($detail->price ?? 0), 
            ];
        }

       
        if (empty($items)) {
            $items[] = [
                'name' => 'Trả hàng về shop',
                'quantity' => 1,
                'price' => 0,
            ];
        }
        
        $orderData = array_merge($fromAddress, $toAddress, [
            'client_order_code' => 'RETURN_' . $order->id,
            'weight' => 500,
            'length' => 30,
            'width' => 20,
            'height' => 10,
            'service_type_id' => 2,
            'items' => $items,
            'note' => 'Trả hàng về shop',
            'required_note' => 'KHONGCHOXEMHANG',  
            'payment_type_id' => 1,
            'cod_amount' => 0,
        ]);

        return $ghnService->createOrder($orderData);
    }

    // Viettel Post: Tạo đơn trả hàng (from: khách, to: shop)
    private static function processViettelPostReturnShipment(Order $order)
    {
        $viettelService = new \App\Services\ViettelPostService();

       
        $viettelProvider = Provider::where('provider_name', 'Viettel Post')->first();
        $viettelProviderId = $viettelProvider->id;

       
        $provinceMap = providerProvinces::where([
            'provider_id' => $viettelProviderId,
            'province_id' => $order->province_id,
        ])->first();

        $districtMap = providerDistrict::where([
            'provider_id' => $viettelProviderId,
            'district_id' => $order->district_id,
        ])->first();

        $wardMap = providerWard::where([
            'provider_id' => $viettelProviderId,
            'ward_id' => $order->ward_id,
        ])->first();

        $fromAddress = [
            'SENDER_FULLNAME' => $order->customer_name,
            'SENDER_ADDRESS' => $order->address,
            'SENDER_PHONE' => $order->phone,
            'SENDER_WARD' => $wardMap?->provider_ward_code,
            'SENDER_DISTRICT' => $districtMap?->provider_district_code,
            'SENDER_PROVINCE' => $provinceMap?->provider_province_code,
        ];
 
        $toAddress = [
            'RECEIVER_FULLNAME' => config('shopConfig.shop_name'),
            'RECEIVER_ADDRESS' => config('shopConfig.shop_address'),
            'RECEIVER_PHONE' => config('shopConfig.shop_phone'),
            'RECEIVER_WARD' => $viettelService->shop_ward,  
            'RECEIVER_DISTRICT' => $viettelService->shop_district,
            'RECEIVER_PROVINCE' => $viettelService->shop_province,
        ];

        $orderData = array_merge($fromAddress, $toAddress, [
            'ORDER_NUMBER' => 'RETURN_' . $order->id . '_' . time(),
            'ORDER_PAYMENT' => 3,  
            'PRODUCT_TYPE' => 'HH',
            'ORDER_SERVICE' => 'VCN',
            'PRODUCT_NAME' => 'Trả hàng về shop',
            'PRODUCT_DESCRIPTION' => 'Trả hàng đơn #' . $order->id,
            'PRODUCT_QUANTITY' => 1,
            'PRODUCT_PRICE' => 0,
            'PRODUCT_WEIGHT' => 500,
        ]);

        // Gọi API tạo đơn trả hàng
        return $viettelService->createOrderFromData($orderData);
    }

    public static function processShipment(Order $record, $shipmentUnit)
    {
        Log::info('OrderShipmentService::processShipment được gọi', [
            'order_id' => $record->id,
            'shipment_unit' => $shipmentUnit,
            'shipment_unit_type' => gettype($shipmentUnit),
            'shipment_unit_lower' => strtolower($shipmentUnit ?? ''),
            'payment_detail' => $record->paymentDetail ? [
                'payment_method' => $record->paymentDetail->payment_method,
                'shipment_unit' => $record->paymentDetail->shipment_unit,
            ] : 'NULL'
        ]);

        try {
            $result = false;
            $shipmentName = '';

            $switchValue = strtolower($shipmentUnit ?? '');
            Log::info('Switch case debug', [
                'switch_value' => $switchValue,
                'is_ghn' => in_array($switchValue, ['giao hàng nhanh', 'giao_hang_nhanh', 'ghn']),
                'is_viettel' => in_array($switchValue, ['viettel post', 'viettel_post', 'viettelpost'])
            ]);

            switch ($switchValue) {
                case 'giao hàng nhanh':
                case 'giao_hang_nhanh':
                case 'ghn':
                    Log::info('Chọn GHN');
                    $shipmentName = 'Giao Hàng Nhanh';
                    $result = self::processGHNShipment($record);
                    break;

                case 'viettel post':
                case 'viettel_post':
                case 'viettelpost':
                    Log::info('Chọn Viettel Post');
                    $shipmentName = 'Viettel Post';

                    $tokenStatus = self::debugViettelPostToken();
                    Log::info('Viettel Post Token Debug', $tokenStatus);

                    if ($tokenStatus['has_token']) {
                        $result = self::processViettelPostShipment($record);
                    } else {
                        Log::info('Skip Viettel Post - chưa có token hợp lệ', [
                            'order_id' => $record->id
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Thông báo!')
                            ->body("Tạm thời chưa thể đăng lên Viettel Post do chưa có token hợp lệ. Đã duyệt đơn hàng.")
                            ->warning()
                            ->send();
                        return;
                    }
                    break;

                default:
                    Log::info('Default case - shipment_unit không khớp', [
                        'shipment_unit' => $shipmentUnit,
                        'switch_value' => $switchValue
                    ]);

                    if (empty($shipmentUnit)) {
                        Log::warning('Không có thông tin đơn vị vận chuyển, sử dụng GHN mặc định', [
                            'order_id' => $record->id
                        ]);
                        $shipmentName = 'Giao Hàng Nhanh (mặc định)';
                        $result = self::processGHNShipment($record);
                    } else {
                        Log::warning('Đơn vị vận chuyển không được hỗ trợ', [
                            'shipment_unit' => $shipmentUnit,
                            'order_id' => $record->id
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Cảnh báo!')
                            ->body("Đơn vị vận chuyển '{$shipmentUnit}' chưa được hỗ trợ! Sử dụng GHN mặc định.")
                            ->warning()
                            ->send();

                        $shipmentName = 'Giao Hàng Nhanh (mặc định)';
                        $result = self::processGHNShipment($record);
                    }
            }


            self::handleShipmentResult($record, $result, $shipmentName);
        } catch (\Exception $e) {
            Log::error("Lỗi đăng đơn vận chuyển: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'order_id' => $record->id,
                'shipment_unit' => $shipmentUnit
            ]);

            \Filament\Notifications\Notification::make()
                ->title('Lỗi!')
                ->body("Lỗi khi đăng đơn vận chuyển: " . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    private static function debugViettelPostToken()
    {
        try {
            $provider = \App\Models\Provider::where('provider_name', 'Viettel Post')->first();

            if (!$provider || !$provider->provider_token) {
                return [
                    'has_token' => false,
                    'message' => 'Không có token Viettel Post trong database'
                ];
            }

            $now = new \DateTime();
            $tokenExpiredTime = new \DateTime($provider->token_expired_time);

            if ($now >= $tokenExpiredTime) {
                return [
                    'has_token' => false,
                    'message' => 'Token Viettel Post đã hết hạn: ' . $provider->token_expired_time
                ];
            }

            return [
                'has_token' => true,
                'message' => 'Token Viettel Post hợp lệ',
                'expired_time' => $provider->token_expired_time
            ];
        } catch (\Exception $e) {
            return [
                'has_token' => false,
                'message' => 'Lỗi check token: ' . $e->getMessage()
            ];
        }
    }

    private static function processGHNShipment(Order $record)
    {
        Log::info('Bắt đầu xử lý GHN shipment', ['order_id' => $record->id]);

        try {
            $ghnService = new \App\Services\GhnService();

            $testResult = $ghnService->testConnection();
            Log::info('GHN Test Connection', $testResult);

            if ($testResult['status'] !== 'success') {
                throw new \Exception('Không thể kết nối GHN API: ' . ($testResult['message'] ?? 'Unknown error'));
            }

            $result = $ghnService->createOrderFromOrder($record->fresh());
            Log::info('GHN Create Order Result', [
                'result' => $result,
                'order_id' => $record->id
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Lỗi GHN shipment: ' . $e->getMessage(), [
                'order_id' => $record->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private static function processViettelPostShipment(Order $record)
    {
        Log::info('Bắt đầu xử lý Viettel Post shipment (REAL API)', ['order_id' => $record->id]);

        try {
            $viettelService = new \App\Services\ViettelPostService();

            $testResult = $viettelService->testConnection();
            Log::info('Viettel Post Test Connection', $testResult);

            if ($testResult['status'] !== 'success') {
                throw new \Exception('Không thể kết nối Viettel Post API: ' . ($testResult['message'] ?? 'Unknown error'));
            }

            $result = $viettelService->createOrder($record);

            Log::info('Viettel Post Create Order Result', [
                'result' => $result,
                'order_id' => $record->id
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Lỗi Viettel Post: ' . $e->getMessage(), [
                'order_id' => $record->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Tạo đơn mock Viettel Post
     */
    private static function createMockViettelPostOrder(Order $record)
    {
        try {
            $mockOrderNumber = 'VTP_MOCK_' . $record->id . '_' . time();

            $totalPrice = 0;
            foreach ($record->orderDetails as $detail) {
                $totalPrice += $detail->price * $detail->quantity;
            }

            $record->update([
                'shipping_order_code' => $mockOrderNumber,
                'shipping_status' => \App\Models\Order::SHIPPING_STATUS_DA_TAO_DON ?? 'da_tao_don',
                'shipping_info' => [
                    'ORDER_NUMBER' => $mockOrderNumber,
                    'service' => 'Viettel Post (Mock)',
                    'note' => 'Đây là mock data cho demo',
                    'created_time' => now()->toISOString(),
                    'total_fee' => 25000,
                    'cod_amount' => $record->paymentDetail->payment_method === 'cod' ? $totalPrice : 0
                ],
                'orders_status' => 'Vận chuyển'
            ]);

            Log::info('Tạo đơn Viettel Post Mock thành công', [
                'order_id' => $record->id,
                'shipping_code' => $mockOrderNumber,
                'total_price' => $totalPrice
            ]);

            return [
                'status' => 200,
                'message' => 'Tạo đơn thành công (Mock)',
                'data' => [
                    'ORDER_NUMBER' => $mockOrderNumber,
                    'service' => 'Viettel Post Mock',
                    'total_fee' => 25000,
                    'expected_delivery' => now()->addDays(3)->format('d/m/Y'),
                    'mock' => true
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Lỗi tạo đơn Viettel Post Mock: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Mock Error: ' . $e->getMessage()
            ];
        }
    }

    private static function handleShipmentResult(Order $record, $result, $shipmentName)
    {
        Log::info('Xử lý kết quả shipment', [
            'order_id' => $record->id,
            'shipment_name' => $shipmentName,
            'result_type' => gettype($result),
            'result' => $result
        ]);

        if ($result && (
            (isset($result['data']) && isset($result['data']['order_code'])) ||
            (isset($result['data']) && isset($result['data']['ORDER_NUMBER'])) ||
            (isset($result['status']) && $result['status'] == 200)
        )) {
            $orderCode = '';
            if (isset($result['data']['order_code'])) {
                $orderCode = $result['data']['order_code'];
            } elseif (isset($result['data']['ORDER_NUMBER'])) {
                $orderCode = $result['data']['ORDER_NUMBER'];
            }

            \Filament\Notifications\Notification::make()
                ->title('Thành công!')
                ->body("Đã duyệt đơn và đăng lên {$shipmentName} thành công!" .
                    ($orderCode ? "\nMã vận đơn: {$orderCode}" : ''))
                ->success()
                ->send();

            activity()
                ->causedBy(Auth::user())
                ->performedOn($record)
                ->log("Duyệt đơn và đăng {$shipmentName}: " . ($orderCode ?: 'Thành công'));
        } else {
            $errorMsg = "Không thể đăng lên {$shipmentName}.";
            if (is_array($result) && isset($result['message'])) {
                $errorMsg .= ' Lỗi: ' . $result['message'];
            } elseif (is_array($result) && isset($result['error'])) {
                $errorMsg .= ' Lỗi: ' . $result['error'];
            }

            \Filament\Notifications\Notification::make()
                ->title('Cảnh báo!')
                ->body($errorMsg)
                ->warning()
                ->send();
        }
    }
}
