<?php

namespace Modules\ViettelPostWebhook\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\ViettelPostStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ViettelPostWebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('viettelpostwebhook::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('viettelpostwebhook::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('viettelpostwebhook::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function handle(Request $request)
    {

        try {
            $method = $request->method();
            $payload = $request->getContent();


            if (!$payload) {
                return response()->json([
                    'error' => 'Du lieu khong hop le'
                ], 400);
            }


            $data = json_decode($payload, true)['DATA'];
            $order_number = $data['ORDER_NUMBER'];

            $order = Order::where('shipping_order_code', $order_number)->first();

            if (!$order) {
                return response()->json([
                    'error' => 'Khong tim thay don hang',
                ], 400);
            }
            $requiredFields = ['ORDER_STATUS', 'LOCALION_CURRENTLY', 'STATUS_NAME'];

            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return response()->json([
                        'error'   => 'Thiếu hoặc rỗng dữ liệu: ' . $field
                    ], 400);
                }
            }

            $order_status = $data['ORDER_STATUS'];
            $insert_data = [
                'shipping_status'   => $order_status,
                'raw_response'      => json_encode($data),
                'current_location'  => $data['LOCALION_CURRENTLY'] ?? null,
                'status_name'       => $data['STATUS_NAME'] ?? ViettelPostStatusEnum::getDescription($order_status) ?? null,
            ];

            $result = $order->webhook()->create($insert_data);

            $general_status = ViettelPostStatusEnum::getOrderStatusLabel($order_status);
            $shipping_status = Order::mapViettelPostStatusToOrderStatus($order_status);

            $order->orders_status = $general_status;
            $order->shipping_status = $shipping_status;
            $order->save();
            if ($result) {
                return response()->json(['success' => 'Da cap nhat thong tin'], 200);
            } else {
                return response()->json(['error' => 'Da co loi khi cap nhat thong tin'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error => ' . $e->getMessage()], 500);
        }
    }
}
