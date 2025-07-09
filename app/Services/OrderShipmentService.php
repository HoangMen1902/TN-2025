<?php
// File: app/Services/OrderShipmentService.php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderShipmentService
{
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

            // Debug switch case
            $switchValue = strtolower($shipmentUnit ?? '');
            Log::info('Switch case debug', [
                'switch_value' => $switchValue,
                'is_ghn' => in_array($switchValue, ['giao hàng nhanh', 'giao_hang_nhanh', 'ghn']),
                'is_viettel' => in_array($switchValue, ['viettel post', 'viettel_post', 'viettelpost'])
            ]);

            // Xác định đơn vị vận chuyển
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

                    // Debug token Viettel Post
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

                    // Mặc định dùng GHN
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

            // Xử lý kết quả
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
        return [
            'has_token' => true,
            'message' => 'Mock token - luôn khả dụng',
            'mock_mode' => true
        ];
    }

    private static function processGHNShipment(Order $record)
    {
        Log::info('Bắt đầu xử lý GHN shipment', ['order_id' => $record->id]);

        try {
            $ghnService = new \App\Services\GhnService();

            // Test connection trước
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
        Log::info('Bắt đầu xử lý Viettel Post shipment (Mock Mode)', ['order_id' => $record->id]);

        try {
            // FORCE MOCK - không cần check token thật
            $result = self::createMockViettelPostOrder($record);

            Log::info('Viettel Post Mock Result', [
                'result' => $result,
                'order_id' => $record->id
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Lỗi Viettel Post Mock: ' . $e->getMessage(), [
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
            // Tạo mã vận đơn giả
            $mockOrderNumber = 'VTP_MOCK_' . $record->id . '_' . time();

            // Tính total price
            $totalPrice = 0;
            foreach ($record->orderDetails as $detail) {
                $totalPrice += $detail->price * $detail->quantity;
            }

            // Cập nhật order
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
            (isset($result['data']) && isset($result['data']['order_code'])) || // GHN
            (isset($result['data']) && isset($result['data']['ORDER_NUMBER'])) || // Viettel Post
            (isset($result['status']) && $result['status'] == 200) // Viettel Post format khác
        )) {
            // Lấy mã vận đơn
            $orderCode = '';
            if (isset($result['data']['order_code'])) {
                $orderCode = $result['data']['order_code']; // GHN
            } elseif (isset($result['data']['ORDER_NUMBER'])) {
                $orderCode = $result['data']['ORDER_NUMBER']; // Viettel Post
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
            // Hiển thị lỗi chi tiết hơn
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
