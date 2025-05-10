<?php

namespace App\Services;

use Illuminate\Container\Attributes\Log;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;

class GhnService
{
    protected $token;

    public function __construct()
    {
        $this->token = env('GHN_API');
    }

    public function getProvinces()
    {
        try {
            $response = Http::withHeaders(['Token' => $this->token])
                ->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');

            if ($response->successful()) {
                $data = $response->json();
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
                return $response->json('data');
            } else {
                Logger('Error fetching districts', ['response' => $response->body()]);
                return [];
            }
        } catch (\Exception $e) {
            Logger('Error fetching districts', ['exception' => $e->getMessage()]);
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
                $data = $response->json('data');
                return $data ?? [];
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
            return $response;
        } catch (\Throwable $th) {
            Logger('Error fetching districts', ['exception' => $th->getMessage()]);
        }
    }

    public function getEstimatedTime($shop_district_id,  $shop_ward_id,  $district_id,  $ward_id)
    {
        try {
            $serviceTypeId = collect($this->getServiceList($shop_district_id, $district_id)['data'] ?? [])
            ->firstWhere('short_name', 'Hàng nhẹ')['service_type_id'] ?? null;
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'ShopId' => (int)env('GHN_SHOPID'),
                'Token' => $this->token
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/leadtime', [
                "from_district_id" => (int)$shop_district_id,
                "from_ward_code" => $shop_ward_id,
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
}
