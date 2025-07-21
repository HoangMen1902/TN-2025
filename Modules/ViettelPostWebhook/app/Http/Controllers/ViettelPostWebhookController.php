<?php

namespace Modules\ViettelPostWebhook\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        $header = $request->header('Authorization');
        $provided_token = env('VIETTELPOST_WEBHOOK_TOKEN');
        $payload = $request->all();

        $payload = $request->all();
        $logData = json_encode($payload, JSON_PRETTY_PRINT);
        file_put_contents(storage_path('logs/order-payload.log'), "[" . now() . "]\n" . $logData . "\n\n", FILE_APPEND);


        if ($header !== $provided_token) {

            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }






        if (empty($payload) || !$payload['DATA']['ORDER_NUMBER'] || !$payload || !$payload['DATA']) {
            return response()->json([
                'status' => 401,
                'data' => [],
                'message' => 'ORDER_KHONG_HOP_LE',
                'token' => $payload['TOKEN'] ?? null,
            ]);
        }

        $orderNumber = $payload['DATA']['ORDER_NUMBER'];


        $order = Order::where('shipping_order_code', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'status' => 401,
                'data' => [],
                'message' => 'ORDER_KHONG_HOP_LE',
                'token' => $payload['TOKEN'] ?? null,
            ]);
        }


        switch ($payload['DATA']['ORDER_STATUS']) {
            case 501:
                $order->orders_status = 'Đã giao';
                break;
            case 107:
            case 201:
                $order->orders_status = 'Đã hủy';
                break;
            case 200:
            case 202:
            case 300:
            case 320:
            case 400:
                $order->orders_status = 'Vận chuyển';
                break;
            default:
                break;
        }


        $order->shipping_info = $payload['DATA'];
        $order->save();
        return response()->json([
            'status' => 200,
            'data' => $payload['DATA'] ?? [],
            'token' => $payload['TOKEN'] ?? null,
            'message' => 'ORDER_DA_GHI_NHAN'
        ]);
    }
}
