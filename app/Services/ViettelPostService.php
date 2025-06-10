<?php

namespace App\Services;

use App\Models\Provider;
use DateTime;
use Exception;
use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Event\Code\Throwable;

class   ViettelPostService
{

    private $shopData;

    private $token;
    private $userData;
    private $shop_province;
    private $shop_district;
    private $shop_ward;

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
}
