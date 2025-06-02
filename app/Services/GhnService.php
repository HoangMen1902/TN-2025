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

    protected $shop_province_id;
    protected $shop_district_id;
    protected $shop_ward_id;

    public function __construct()
    {

        $this->token = env('GHN_API');
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
            $response = Http::withHeaders(['Token' => $this->token])
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
            $response = Http::withHeaders(['Token' => $this->token])
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
            $response = Http::withHeaders(['Token' => $this->token])
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
                'token' => $this->token
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
                "shop_id" => $shop_id,
                "from_district" => (int)$shop_district,
                "to_district" => (int)$district
            ]);
            if($response->ok()) {
                return $response;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Logger('Error fetching districts', ['exception' => $th->getMessage()]);
        }
    }

    public function getEstimatedTime( $district_id,  $ward_id)
    {
        try {
            $serviceTypeId = collect($this->getServiceList($this->shop_district_id, $district_id)['data'] ?? [])
                ->firstWhere('short_name', 'Hàng nhẹ')['service_type_id'] ?? null;
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->token
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
            $respone = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->token
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
            return $respone->body();
        } catch (Exception $e) {
            Log::error('Lỗi khi tính phí: ' . $e->getMessage());
            return false;
        }
    }
}
