<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Modules\SuggestModule\Http\Controllers\SuggestModuleController;
use Illuminate\Support\Facades\Log;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('suggestmodules', SuggestModuleController::class)->names('suggestmodule');
});
Route::get('/test-order-token-tracking/{orderCode}', function ($orderCode) {
    try {
        $orderToken = env('GHN_TOKEN');
        $shopId = env('GHN_SHOPID');

        $response = Http::withHeaders([
            'Token' => $orderToken,
            'ShopId' => (int)$shopId,
            'Content-Type' => 'application/json'
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
            'order_code' => $orderCode
        ]);

        return response()->json([
            'order_code' => $orderCode,
            'token_type' => 'orderToken (same as createOrder)',
            'token_used' => substr($orderToken, 0, 10) . '...',
            'shop_id' => $shopId,
            'status' => $response->status(),
            'successful' => $response->successful(),
            'response' => $response->json(),
            'note' => 'Using same token as createOrder method'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ]);
    }
});
Route::get('/create-and-track-ghn', function () {
    try {
        // Tìm order chưa có shipping code
        $order = \App\Models\Order::whereNull('shipping_order_code')
            ->whereHas('paymentDetail')
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy order phù hợp']);
        }

        $ghnService = new \App\Services\GhnService();

        // Tạo đơn
        $createResult = $ghnService->createOrderFromOrder($order);

        if ($createResult && isset($createResult['data']['order_code'])) {
            $newOrderCode = $createResult['data']['order_code'];

            // Đợi 2 giây cho GHN xử lý
            sleep(2);

            // Test tracking ngay
            $trackResult = $ghnService->trackOrder($newOrderCode);

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'create_result' => $createResult,
                'new_order_code' => $newOrderCode,
                'track_result' => $trackResult,
                'tracking_works' => $trackResult !== false
            ]);
        } else {
            return response()->json([
                'status' => 'create_failed',
                'result' => $createResult
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});
