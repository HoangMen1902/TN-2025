<?php

namespace App\Services;

use App\Models\Provider;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class   ViettelPostService
{

    private $shopData;

    private $userData;


    public function fetchWard() {
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


    public function fetchDistrict() {
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

    public function updateToken()
    {
        $provider = Provider::where('provider_name', '=', 'Viettel Post')->first();
        if ($provider) {
            $token = $this->getTokenBindData();
            $provider->provider_token = $token;
            $expired_time = date('Y-m-d H:i:s', $this->shopData->expired / 1000);
            $provider->token_expired_time = $expired_time;
            $provider->save();
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
