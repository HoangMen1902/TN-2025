<?php

namespace App\Services;

use App\Models\District;
use App\Models\Provider;
use App\Models\Province;
use App\Models\Ward;
use DateTime;
use Exception;
use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Event\Code\Throwable;


class ViettelPostService
{
    private $shopData;
    private $token;
    private $userData;
    private $shop_province;
    private $shop_district;
    private $shop_ward;
    private $addressToken;
    private $cusId;

    public function __construct()
    {
        $provider = Provider::where('provider_name', 'Viettel Post')->first();
        if (!$provider) {
            $provider = Provider::updateOrCreate(
                ['provider_name' => 'Viettel Post'],
                ['provider_status' => 'active']
            );
        }
        $now = new DateTime();
        $token_expired_time = new Datetime($provider->token_expired_time);
        if ($provider->provider_token &&  $now < $token_expired_time) {
            $this->token = Crypt::decrypt($provider->provider_token);
        } else {
            Log::info('Vui lòng cập nhật token ViettelPost');
        }

        $provinceNameFromConfig = config('shopConfig.shop_province');
        $districtNameFromConfig = config('shopConfig.shop_district');
        $wardNameFromConfig = config('shopConfig.shop_ward');

        $shop_province = collect($this->fetchProvince() ?? [])
            ->first(function ($item) use ($provinceNameFromConfig) {
                return Str::contains(Str::lower($item['PROVINCE_NAME']), Str::lower($provinceNameFromConfig));
            });
        if (empty($shop_province)) {
            return;
        }
        $this->shop_province = $shop_province['PROVINCE_ID'];

        $shop_district = collect($this->fetchDistrict() ?? [])
            ->first(function ($item) use ($districtNameFromConfig) {
                return Str::contains(Str::lower($item['DISTRICT_NAME']), Str::lower($districtNameFromConfig));
            });
        if (empty($shop_district)) {
            return;
        }
        $this->shop_district = $shop_district['DISTRICT_ID'];

        $shop_ward_id = collect($this->fetchWard() ?? [])
            ->first(function ($item) use ($wardNameFromConfig) {
                return Str::contains(Str::lower($item['WARDS_NAME']), Str::lower($wardNameFromConfig));
            });
        $this->shop_ward = $shop_ward_id['WARDS_ID'];

        $this->addressToken = env('VIETTELPOST_GROUP_ADDRESS_ID');
        $this->cusId = env('VIETTELPOST_CUS_ID');
        Log::info('ViettelPostService addressToken debug', ['addressToken' => $this->addressToken]);
    }


    public function fetchWard()
    {
        try {
            $response = Http::get('https://partner.viettelpost.vn/v2/categories/listWards?districtId=-1');
            if ($response->successful()) {
                $data = json_decode($response, true)['data'];
                return $data;
            } else {
                Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI Ward: " . $response->body());
                return [];
            }
        } catch (Exception $e) {
            Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI Ward: " . $e->getMessage());
            return [];
        }
    }


    public function fetchDistrict()
    {
        try {
            $response = Http::get('https://partner.viettelpost.vn/v2/categories/listDistrict?provinceId=-1');
            if ($response->successful()) {
                $data = json_decode($response, true)['data'];
                return $data;
            } else {
                Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI District: " . $response->body());
                return [];
            }
        } catch (Exception $e) {
            Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI District: " . $e->getMessage());
            return [];
        }
    }

    public function fetchProvince()
    {
        try {
            $response = Http::get('https://partner.viettelpost.vn/v2/categories/listProvinceById?provinceId=-1');
            if ($response->successful()) {
                $data = json_decode($response, true)['data'];
                return $data;
            } else {
                Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI: " . $response->body());
                return [];
            }
        } catch (Exception $e) {
            Log::error("Đã có lỗi xảy ra trong quá trình fetch dữ liệu tỉnh ViettelAPI: " . $e->getMessage());
            return [];
        }
    }

    public function getFee($order_cost, array $size, $reciever_province_id, $reciever_district_id, $paymentMethod = 'COD')
    {
        try {
            $response = Http::withHeaders([
                'token' => $this->token
            ])->post('https://partner.viettelpost.vn/v2/order/getPrice', [
                "PRODUCT_WEIGHT" => $size['weight'],
                "PRODUCT_WIDTH" => $size['width'],
                "PRODUCT_LENGTH" => $size['length'],
                "PRODUCT_HEIGHT" => $size['height'],
                "PRODUCT_PRICE" => $order_cost,
                "MONEY_COLLECTION" => $paymentMethod === "COD" ? $order_cost : 0,
                "ORDER_SERVICE_ADD" => "",
                "ORDER_SERVICE" => "VCN",
                "SENDER_PROVINCE" => $this->shop_province,
                "SENDER_DISTRICT" => $this->shop_district,
                "RECEIVER_PROVINCE" => $reciever_province_id,
                "RECEIVER_DISTRICT" => $reciever_district_id,
                "PRODUCT_TYPE" => "HH",
                "NATIONAL_TYPE" => 1
            ]);
            if ($response->successful()) {
                return $response->body();
            } else {
                Log::error('Đã có lỗi xảy ra khi tính phí giao hàng ViettelPost');

                return false;
            }
        } catch (\Throwable $th) {
            Log::error('Đã có lỗi xảy ra khi tính phí giao hàng ViettelPost' . $th->getMessage());
            return false;
        }
    }
    public function updateToken()
    {
        $provider = Provider::where('provider_name', '=', 'Viettel Post')->first();
        if ($provider) {
            $token = $this->getTokenBindData();
            $provider->provider_token = Crypt::encrypt($token);
            $expired_time = date('Y-m-d H:i:s', $this->shopData->expired / 1000);
            $provider->token_expired_time = $expired_time;
            $provider->save();
            Info('Đã lưu thành công token vào database');
            return true;
        } else {
            echo "Lỗi, Đơn vị vận chuyển ViettelPost chưa tồn tại trong cơ sở dữ liệu";
            Log::error("Lỗi, Đơn vị vận chuyển ViettelPost chưa tồn tại trong cơ sở dữ liệu");
            return false;
        }
    }

    /**
     * Hàm này được dùng để lấy token của viettel post, tiện thể bind luôn data vào $shopData ở trên trong trường hợp cần dùng.
     * return token
     */
    public function getTokenBindData()
    {
        try {
            $this->userData = ["USERNAME" => env("VIETTELPOST_USERNAME"), "PASSWORD" => env("VIETTELPOST_PASSWORD")];
            $response = Http::post('https://partner.viettelpost.vn/v2/user/Login', $this->userData);
            if ($response->successful()) {
                $data = json_decode($response->body())->data;
                $this->shopData = $data;
                return $data->token;
            } else {
                Log::error('Lỗi khi lấy Token: ' . $response->body());
                return [];
            }
        } catch (Exception $e) {
            Log::error('Lỗi khi lấy Token: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Test kết nối API
     */
    public function testConnection()
    {
        try {
            if (!$this->token) {
                return [
                    'status' => 'error',
                    'message' => 'Không có token Viettel Post'
                ];
            }


            $provinces = $this->fetchProvince();

            if (!empty($provinces)) {
                return [
                    'status' => 'success',
                    'message' => 'Kết nối Viettel Post API thành công!',
                    'token' => substr($this->token, 0, 10) . '...',
                    'provinces_count' => count($provinces)
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Không thể lấy dữ liệu từ Viettel Post API'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi kết nối: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tạo đơn hàng Viettel Post
     */
    /**
     * Tạo đơn hàng Viettel Post
     */
    public function createOrder(\App\Models\Order $order)
    {
        try {
            Log::info('Bắt đầu tạo đơn Viettel Post', ['order_id' => $order->id]);

            if (!$this->token) {
                Log::error('Không có token Viettel Post');
                return [
                    'status' => 'error',
                    'message' => 'Không có token Viettel Post'
                ];
            }



            $orderData = $this->prepareOrderData($order);


            // Log::info('Dữ liệu đơn hàng Viettel Post (JSON encode)', [
            //     'order_data' => $orderData,
            //     'data_json' => $orderDataJson,
            //     'data_size' => strlen($orderDataJson) . ' bytes'
            // ]);


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])->timeout(30)
                ->post('https://partner.viettelpost.vn/v2/order/createOrder', $orderData);
            Log::info('Viettel Post API Response Details', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'body_length' => strlen($response->body()),
                'successful' => $response->successful(),
                'failed' => $response->failed()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Viettel Post Response JSON', $data);

                if (isset($data['status']) && $data['status'] == 200 && isset($data['data']['ORDER_NUMBER'])) {

                    $order->update([
                        'shipping_order_code' => $data['data']['ORDER_NUMBER'],
                        'shipping_status' => \App\Models\Order::SHIPPING_STATUS_DA_TAO_DON ?? 'da_tao_don',
                        // 'shipping_info' => $data['data'],
                        'orders_status' => 'Vận chuyển'
                    ]);

                    Log::info('Tạo đơn Viettel Post thành công', [
                        'order_id' => $order->id,
                        'shipping_code' => $data['data']['ORDER_NUMBER']
                    ]);

                    return $data;
                } else {
                    Log::error('Tạo đơn Viettel Post thất bại - Response không hợp lệ: ', $data);
                    return [
                        'status' => 'error',
                        'message' => $data['message'] ?? 'Phản hồi API không hợp lệ',
                        'api_response' => $data
                    ];
                }
            } else {
                Log::error('Tạo đơn Viettel Post thất bại: ' . $response->body());
                return [
                    'status' => 'error',
                    'message' => 'HTTP Error: ' . $response->status(),
                    'response_body' => $response->body()
                ];
            }
            if ($response->failed()) {
                Log::error('Tạo đơn Viettel Post thất bại', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                    'json' => $response->json(),
                    'order_data' => $orderData,
                ]);
                return [
                    'status' => 'error',
                    'message' => 'HTTP Error: ' . $response->status(),
                    'response_body' => $response->body(),
                    'response_json' => $response->json(),
                    'response_headers' => $response->headers(),
                    'order_data' => $orderData,
                ];
            }
        } catch (Exception $e) {
            Log::error('Lỗi tạo đơn Viettel Post: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tạo đơn mock cho test
     */
    private function createMockOrder(\App\Models\Order $order)
    {
        try {

            $mockOrderNumber = 'VTP_MOCK_' . $order->id . '_' . time();


            $order->update([
                'shipping_order_code' => $mockOrderNumber,
                'shipping_status' => \App\Models\Order::SHIPPING_STATUS_DA_TAO_DON ?? 'da_tao_don',
                'shipping_info' => [
                    'ORDER_NUMBER' => $mockOrderNumber,
                    'service' => 'ViettelPost Mock',
                    'note' => 'Đây là mock data cho test',
                    'created_time' => now()->toISOString()
                ],
                'orders_status' => 'Vận chuyển'
            ]);

            Log::info('Tạo đơn Viettel Post Mock thành công', [
                'order_id' => $order->id,
                'shipping_code' => $mockOrderNumber
            ]);

            return [
                'status' => 200,
                'message' => 'Tạo đơn thành công (Mock)',
                'data' => [
                    'ORDER_NUMBER' => $mockOrderNumber,
                    'service' => 'ViettelPost Mock',
                    'mock' => true
                ]
            ];
        } catch (Exception $e) {
            Log::error('Lỗi tạo đơn Viettel Post Mock: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Mock Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Chuẩn bị dữ liệu đơn hàng - CẬP NHẬT
     */
    private function prepareOrderData(\App\Models\Order $order)
    {



        $senderAddress = $this->getShopViettelAddress();

        Log::info('Địa chỉ đã map cho Viettel Post', [
            'sender' => $senderAddress,
        ]);

        $totalWeight = 0;
        $productTotal = 0;
        $products = [];

        foreach ($order->orderDetails as $detail) {
            if ($detail->item_type === 'sku' && $detail->sku) {
                $weight = $detail->sku->product->weight ?? 500;
                $totalWeight += $weight * $detail->quantity;
                $productTotal += $detail->price * $detail->quantity;

                $products[] = [
                    'PRODUCT_NAME' => $this->cleanProductName($detail->sku->product->name ?? 'Sản phẩm'),
                    'PRODUCT_QUANTITY' => (int)$detail->quantity,
                    'PRODUCT_PRICE' => (int)$detail->price,
                    'PRODUCT_WEIGHT' => (int)$weight
                ];
            } elseif ($detail->item_type === 'combo' && $detail->combo) {
                $weight = 500;
                $totalWeight += $weight * $detail->quantity;
                $productTotal += $detail->price * $detail->quantity;

                $products[] = [
                    'PRODUCT_NAME' => $this->cleanProductName($detail->combo->name ?? 'Combo'),
                    'PRODUCT_QUANTITY' => (int)$detail->quantity,
                    'PRODUCT_PRICE' => number_format($detail->price, 2, '.', ''),
                    'PRODUCT_WEIGHT' => (int)$weight
                ];
            }
        }


        $paymentMethod = $order->paymentDetail->payment_method ?? 'cod';
        $codAmount = ($paymentMethod === 'cod') ? $productTotal : 0;

        $province = Province::find($order->province_id);
        $district = District::find($order->district_id);
        $ward = Ward::find($order->ward_id);


        $senderWard = isset($senderAddress['ward_id']) ? (int)$senderAddress['ward_id'] : 0;
        $senderDistrict = isset($senderAddress['district_id']) ? (int)$senderAddress['district_id'] : 0;
        $senderProvince = isset($senderAddress['province_id']) ? (int)$senderAddress['province_id'] : 0;
        $receiverWard = $ward->providerWard->provider_ward_code ?? 0;
        $receiverDistrict = $district->provider_district->provider_district_code ?? 0;
        $receiverProvince = $province->provider_province->provider_province_code ?? 0;;
        // return [
        //     'ORDER_NUMBER' => 'VTP_' . $order->id . '_' . time(),
        //     'GROUPADDRESS_ID' => $this->addressToken,
        //     'CUS_ID' => 0,
        //     'DELIVERY_DATE' => now()->addDay()->format('d/m/Y H:i:s'),

        // 'SENDER_FULLNAME' => config('shopConfig.shop_name'),
        // 'SENDER_ADDRESS' => config('shopConfig.shop_address'),
        // 'SENDER_PHONE' => config('shopConfig.shop_phone'),
        // 'SENDER_EMAIL' => '',
        // 'SENDER_WARD' => (int)$senderWard,
        // 'SENDER_DISTRICT' => (int)$senderDistrict,
        // 'SENDER_PROVINCE' => (int)$senderProvince,

        // 'RECEIVER_FULLNAME' => $order->customer_name,
        // 'RECEIVER_ADDRESS' => $order->address,
        // 'RECEIVER_PHONE' => $order->phone,
        // 'RECEIVER_EMAIL' => '',
        // 'RECEIVER_WARD' => (int)$receiverWard,
        // 'RECEIVER_DISTRICT' => (int)$receiverDistrict,
        // 'RECEIVER_PROVINCE' => (int)$receiverProvince,

        // 'PRODUCT_NAME' => $this->cleanProductName(implode(', ', array_column($products, 'PRODUCT_NAME'))),
        // 'PRODUCT_DESCRIPTION' => 'Đơn hàng #' . $order->id,
        // 'PRODUCT_QUANTITY' => (int)array_sum(array_column($products, 'PRODUCT_QUANTITY')),
        // 'PRODUCT_PRICE' => (float)$productTotal,
        // 'PRODUCT_WEIGHT' => (float)max($totalWeight, 500),
        // 'PRODUCT_LENGTH' => 30,
        // 'PRODUCT_WIDTH' => 20,
        // 'PRODUCT_HEIGHT' => 10,
        //     'PRODUCT_TYPE' => 'HH',
        //     'ORDER_PAYMENT' => $paymentMethod === 'cod' ? 1 : 3,
        //     'ORDER_SERVICE' => 'VNC',
        //     'ORDER_SERVICE_ADD' => '',
        //     'ORDER_VOUCHER' => '',
        //     'ORDER_NOTE' => $this->generateOrderNote($order, $paymentMethod, $productTotal),
        //     'MONEY_COLLECTION' => (float)$codAmount,
        //     'MONEY_TOTALFEE' => 0,
        //     'MONEY_FEECOD' => 0,
        //     'MONEY_FEEVAS' => 0,
        //     'MONEY_FEEINSUR' => 0,
        //     'MONEY_FEE' => 0,
        //     'MONEY_FEEOTHER' => 0,
        //     'MONEY_TOTALVAT' => 0,
        //     'MONEY_TOTAL' => 0,
        //     'NATIONAL_TYPE' => 1,
        //     'ORDER_SPECIAL' => '',
        //     'LIST_ITEM' => $products
        // ];



        // $data = [
        //     "ORDER_NUMBER" => 'VTP_' . $order->id . '_' . time(),
        //     // 'GROUPADDRESS_ID' => $this->addressToken,
        //     'SENDER_FULLNAME' => config('shopConfig.shop_name'),
        //     'SENDER_ADDRESS' => config('shopConfig.shop_address'),
        //     'SENDER_PHONE' => config('shopConfig.shop_phone'),
        //     'SENDER_EMAIL' => '',
        //     // 'CUS_ID' => $this->cusId,
        //     // 'SENDER_WARD' => (int)$senderWard,
        //     // 'SENDER_DISTRICT' => (int)$senderDistrict,
        //     // 'SENDER_PROVINCE' => (int)$senderProvince,
        //     'RECEIVER_FULLNAME' => $order->customer_name,
        //     'RECEIVER_ADDRESS' => $order->address,
        //     'RECEIVER_PHONE' => $order->phone,
        //     'RECEIVER_EMAIL' => '',
        //     // 'RECEIVER_WARD' => (int)$receiverWard,
        //     // 'RECEIVER_DISTRICT' => (int)$receiverDistrict,
        //     // 'RECEIVER_PROVINCE' => (int)$receiverProvince,
        //     "ORDER_PAYMENT" => 1,
        //     "PRODUCT_TYPE" => "HH",
        //     "ORDER_SERVICE" => "VCN",
        // 'PRODUCT_NAME' => $this->cleanProductName(implode(', ', array_column($products, 'PRODUCT_NAME'))),
        // 'PRODUCT_DESCRIPTION' => 'Đơn hàng #' . $order->id,
        // 'PRODUCT_QUANTITY' => (int)array_sum(array_column($products, 'PRODUCT_QUANTITY')),
        // 'PRODUCT_PRICE' => (float)$productTotal,
        // 'PRODUCT_WEIGHT' => (float)max($totalWeight, 500),
        // 'PRODUCT_LENGTH' => 30,
        // 'PRODUCT_WIDTH' => 20,
        // 'PRODUCT_HEIGHT' => 10,
        //     'ORDER_NOTE' => $this->generateOrderNote($order, $paymentMethod, $productTotal),
        //     'MONEY_COLLECTION' => (float)$codAmount,
        //     'MONEY_TOTALFEE' => 0,
        //     'MONEY_FEECOD' => 0,
        //     'MONEY_FEEVAS' => 0,
        //     'MONEY_FEEINSUR' => 0,
        //     'MONEY_FEE' => 0,
        //     'MONEY_FEEOTHER' => 0,
        //     'MONEY_TOTALVAT' => 0,
        //     'MONEY_TOTAL' => 0,
        //     'NATIONAL_TYPE' => 1,
        //     'ORDER_SPECIAL' => '',
        //     'LIST_ITEM' => $products
        // ];
        // return $data;


        $data = [
            "ORDER_NUMBER" => 'VTP_' . $order->id . '_' . time(),
            'SENDER_FULLNAME' => config('shopConfig.shop_name'),
            'SENDER_ADDRESS' => config('shopConfig.shop_address'),
            'SENDER_PHONE' => config('shopConfig.shop_phone'),
            "RECEIVER_ADDRESS" => $order->address,
            'RECEIVER_FULLNAME' => $order->customer_name,
            'RECEIVER_PHONE' => $order->phone,
            "ORDER_PAYMENT" => 1,
            "PRODUCT_TYPE" => "HH",
            "ORDER_SERVICE" => "VCN",
            'PRODUCT_NAME' => $this->cleanProductName(implode(', ', array_column($products, 'PRODUCT_NAME'))),
            'PRODUCT_DESCRIPTION' => 'Đơn hàng #' . $order->id,
            'PRODUCT_QUANTITY' => (int)array_sum(array_column($products, 'PRODUCT_QUANTITY')),
            'PRODUCT_PRICE' => (float)$productTotal,
            'PRODUCT_WEIGHT' => (float)max($totalWeight, 500),
            'LIST_ITEM' => $products
        ];
        return $data;
    }
    /**
     * Tạo ghi chú đơn hàng
     */
    private function generateOrderNote(\App\Models\Order $order, $paymentMethod, $productTotal)
    {
        $note = 'Đơn hàng #' . $order->id;

        switch ($paymentMethod) {
            case 'cod':
                $note .= ' - Thu COD ' . number_format($productTotal) . 'đ';
                break;
            case 'vnpay':
                $note .= ' - Đã thanh toán VNPay';
                break;
            case 'momo':
                $note .= ' - Đã thanh toán MoMo';
                break;
            case 'bank_transfer':
                $note .= ' - Đã chuyển khoản';
                break;
            case 'international':
                $note .= ' - Đã thanh toán quốc tế';
                break;
            default:
                $note .= ' - Đã thanh toán online';
        }

        return $note;
    }

    /**
     * Map địa chỉ từ order sang Viettel Post ID thông qua mapping table
     */
    private function mapAddressToViettelPost(\App\Models\Order $order)
    {
        try {
            // Log::info('Bắt đầu map địa chỉ Viettel Post', [
            //     'order_id' => $order->id,
            //     'address' => $order->address
            // ]);


            $checkoutAddress = \App\Models\CheckoutAddress::where('user_id', $order->user_id)
                ->where('customer_name', $order->customer_name)
                ->where('phone', $order->phone)
                ->first();

            if ($checkoutAddress) {

                $viettelProvider = \App\Models\Provider::where('provider_name', 'Viettel Post')->first();

                if (!$viettelProvider) {
                    Log::warning('Không tìm thấy provider Viettel Post');
                    return $this->getDefaultViettelAddress();
                }


                $viettelProvince = \App\Models\providerProvinces::where('provider_id', $viettelProvider->id)
                    ->where('province_id', $checkoutAddress->province_id)
                    ->first();


                $viettelDistrict = \App\Models\providerDistrict::where('provider_id', $viettelProvider->id)
                    ->where('district_id', $checkoutAddress->district_id)
                    ->first();


                $viettelWard = \App\Models\providerWard::where('provider_id', $viettelProvider->id)
                    ->where('ward_id', $checkoutAddress->ward_id)
                    ->first();

                if ($viettelProvince && $viettelDistrict && $viettelWard) {
                    Log::info('Map địa chỉ Viettel Post thành công', [
                        'province_code' => $viettelProvince->provider_province_code,
                        'district_code' => $viettelDistrict->provider_district_code,
                        'ward_code' => $viettelWard->provider_ward_code
                    ]);

                    return [
                        'province_id' => $viettelProvince->provider_province_code,
                        'district_id' => $viettelDistrict->provider_district_code,
                        'ward_id' => $viettelWard->provider_ward_code
                    ];
                } else {
                    Log::warning('Không tìm thấy mapping đầy đủ cho Viettel Post', [
                        'province_mapped' => $viettelProvince ? true : false,
                        'district_mapped' => $viettelDistrict ? true : false,
                        'ward_mapped' => $viettelWard ? true : false
                    ]);
                }
            }


            return $this->parseAddressText($order->address);
        } catch (\Exception $e) {
            Log::error('Lỗi map địa chỉ Viettel Post: ' . $e->getMessage());
            return $this->getDefaultViettelAddress();
        }
    }

    /**
     * Parse địa chỉ từ text và map với database
     */
    private function parseAddressText($address)
    {
        try {

            $parts = array_map('trim', explode(',', $address));

            if (count($parts) < 3) {
                Log::warning('Địa chỉ không đủ thông tin', ['address' => $address]);
                return $this->getDefaultViettelAddress();
            }

            $wardName = str_replace(['Phường ', 'Xã ', 'Thị trấn '], '', $parts[1] ?? '');
            $districtName = str_replace(['Quận ', 'Huyện ', 'Thành phố ', 'Thị xã '], '', $parts[2] ?? '');
            $provinceName = str_replace(['Tỉnh ', 'Thành phố '], '', $parts[3] ?? $parts[2] ?? '');

            Log::info('Parse address text', [
                'ward' => $wardName,
                'district' => $districtName,
                'province' => $provinceName
            ]);


            $viettelProvider = \App\Models\Provider::where('provider_name', 'Viettel Post')->first();

            if (!$viettelProvider) {
                return $this->getDefaultViettelAddress();
            }


            $province = \App\Models\Province::where('name', 'like', "%{$provinceName}%")->first();
            $district = null;
            $ward = null;

            if ($province) {

                $district = \App\Models\District::where('province_id', $province->id)
                    ->where('name', 'like', "%{$districtName}%")
                    ->first();

                if ($district) {

                    $ward = \App\Models\Ward::where('district_id', $district->id)
                        ->where('name', 'like', "%{$wardName}%")
                        ->first();
                }
            }

            if ($province && $district && $ward) {
                $viettelProvince = \App\Models\providerProvinces::where('provider_id', $viettelProvider->id)
                    ->where('province_id', $province->id)->first();
                $viettelDistrict = \App\Models\providerDistrict::where('provider_id', $viettelProvider->id)
                    ->where('district_id', $district->id)->first();
                $viettelWard = \App\Models\providerWard::where('provider_id', $viettelProvider->id)
                    ->where('ward_id', $ward->id)->first();

                if ($viettelProvince && $viettelDistrict && $viettelWard) {
                    return [
                        'province_id' => $viettelProvince->provider_province_code,
                        'district_id' => $viettelDistrict->provider_district_code,
                        'ward_id' => $viettelWard->provider_ward_code
                    ];
                }
            }

            return $this->getDefaultViettelAddress();
        } catch (\Exception $e) {
            Log::error('Lỗi parse address text: ' . $e->getMessage());
            return $this->getDefaultViettelAddress();
        }
    }

    /**
     * Địa chỉ mặc định Viettel Post
     */
    private function getDefaultViettelAddress()
    {
        return [
            'province_id' => 202,
            'district_id' => 1570,
            'ward_id' => 21211
        ];
    }



    /**
     * Lấy địa chỉ shop cho Viettel Post
     */
    private function getShopViettelAddress()
    {
        // Sử dụng địa chỉ đã setup trong constructor
        return [
            'province_id' => $this->shop_province ?? 202,
            'district_id' => $this->shop_district ?? 1570,
            'ward_id' => $this->shop_ward ?? 21211
        ];
    }

    /**
     * Clean product name (loại bỏ ký tự đặc biệt)
     */
    private function cleanProductName($productName)
    {
        // Loại bỏ ký tự có thể gây lỗi API
        $productName = preg_replace('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', '', $productName);
        $productName = preg_replace('/[^\p{L}\p{N}\s\-\.,]/u', '', $productName);
        return trim($productName) ?: 'Sản phẩm';
    }

    public function trackOrder($trackingId)
    {
        try {
            $requestData = [
                'ORDER_NUMBER' => $trackingId,
                'GROUPADDRESS_ID' => $this->addressToken  
            ];
            Log::info('ViettelPost tracking request', [
                'tracking_id' => $trackingId,
                'token' => substr($this->token, 0, 10) . '...',
                'endpoint' => 'https://partner.viettelpost.vn/v2/order/getOrderDetail',
                'request_data' => $requestData
            ]);
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Token' => $this->token,
                'Content-Type' => 'application/json',
            ])->post('https://partner.viettelpost.vn/v2/order/getOrderDetail', $requestData);

            Log::info('ViettelPost tracking response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('ViettelPost tracking error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'json' => $response->json(),
                    'headers' => $response->headers(),
                    'request_data' => $requestData
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('ViettelPost tracking exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tracking_id' => $trackingId
            ]);
            return false;
        }
    }
}
