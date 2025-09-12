<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\VoucherUsed;
use App\Services\GhnService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ShipmentCalculate extends Component
{
    public $store_province;
    public $store_district;

    public $store_ward;
    public $province_default;
    public $district_default;
    public $ward_default;
    public $modal_open = false;
    public $province_id;
    public $district_id;
    public $ward_id;
    public $provinces = [];
    public $districts = [];
    public $wards = [];
    public $estimatedTime;
    public $data;

    public $currentSku;
    public $currentEbook;

    public $content_enable = true;

    public $type;
    protected $rules = [
        'province_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
    ];

    public $vouchers;

    public function setStoreLocation(GhnService $ghn)
    {

        $store_province_data = collect($this->provinces)->firstWhere('ProvinceName', 'Cần Thơ');
        $query_district = $ghn->getDistricts($store_province_data['ProvinceID']);
        $store_district_data = collect($query_district)->firstWhere('DistrictName', 'Quận Cái Răng');
        $query_ward = $ghn->getWards($store_district_data['DistrictID']);
        $store_ward_data = collect($query_ward)->firstWhere('WardName', 'Phường Thường Thạnh');

        $this->store_province = $store_province_data['ProvinceID'];
        $this->store_district = $store_district_data['DistrictID'];
        $this->store_ward = $store_ward_data['WardCode'];
    }

    public function setDefaultLocation(GhnService $ghn)
    {
        $default_province_data = collect($this->provinces)->firstWhere('ProvinceName', 'Hồ Chí Minh');
        $query_district = $ghn->getDistricts($default_province_data['ProvinceID']);
        $default_district_data = collect($query_district)->firstWhere('DistrictName', 'Quận 10');
        $query_ward = $ghn->getWards($default_district_data['DistrictID']);
        $default_ward_data = collect($query_ward)->firstWhere('WardName', 'Phường 1');

        $this->province_default = $default_province_data;
        $this->district_default = $default_district_data;
        $this->ward_default = $default_ward_data;
    }

    public function mount(GhnService $ghn)
    {
        try {
            $this->modal_open = false;
            $this->provinces = $ghn->getProvinces();
            $this->setDefaultLocation($ghn);
            $this->setStoreLocation($ghn);
            $res = $ghn->getEstimatedTime($this->store_district, $this->store_ward, (string)$this->district_default['DistrictID'], $this->ward_default['WardCode']);
            $estimated = $res['data']['leadtime_order']['to_estimate_date'];
            $date = Carbon::parse($estimated)->setTimezone('Asia/Ho_Chi_Minh');
            $this->estimatedTime = ucwords($date->translatedFormat('l - d/m'));
            // $this->currentSku = $this->data->productSkus->first();
          
            // $this->currentSku = $this->data->productSkus()
            // ->with('ebook')
            // ->first();
          
            $this->data->load(['productSkus.ebook', 'productSkus.skuValues.option', 'productSkus.skuValues.value']);

            
            if ($this->data->productSkus->count() === 1) {
                $this->currentSku = $this->data->productSkus->first();
            }
        
            $current_price = $this->currentSku->sale_price ?? $this->currentSku->price ?? 0;

            $user_id = Auth::id();
            $this->vouchers = VoucherUsed::with('voucher')
                ->where('user_id', $user_id)
                ->where('is_used', false)
                ->whereHas('voucher', function ($query) use ($current_price) {
                    $query->where('expired_at', '>', now())
                        ->where('requirement_price', '<=', $current_price);
                })
                ->get();
        } catch (\Throwable $th) {
            Log::error('Loi khi fetch du lieu: ' . $th->getMessage());
        }
    }

    public function selectSku($skuId)
    {
        $currentSku = $this->data->productSkus->firstWhere('id', $skuId);
        if ($currentSku) {
            $this->currentSku = $currentSku;
            $this->dispatch('updatedSku', skuId: $skuId);
        }
    }

    #[On('toogleContent')]
    public function toogleContent($status) {
        if($status === false) {
            $this->content_enable = false;
        } else {
            $this->content_enable = true;
        }
    }

    // public function selectSku($skuId)
    // {
    //     $currentSku = $this->data->productSkus()
    //         ->with('ebook')
    //         ->firstWhere('id', $skuId);
    
    //     if ($currentSku) {
    //         $this->currentSku = $currentSku;
    //         $this->dispatch('updatedSku', skuId: $skuId);
    //     }
    // }
    
    function updateTime(GhnService $ghn)
    {
        $this->validate();
        Carbon::setLocale('vi');
        $res = $ghn->getEstimatedTime($this->store_district, $this->store_ward, $this->district_id, $this->ward_id);
        $estimated = $res['data']['leadtime_order']['to_estimate_date'];
        $date = Carbon::parse($estimated)->setTimezone('Asia/Ho_Chi_Minh');
        $this->estimatedTime = ucwords($date->translatedFormat('l - d/m'));
        $this->modal_open = false;
        $this->updateCustomerLocation();
    }

    public function updateCustomerLocation()
    {
        $customer_province = collect($this->provinces)->firstWhere('ProvinceID', $this->province_id);
        $customer_district = collect($this->districts)->firstWhere('DistrictID', $this->district_id);
        $customer_ward = collect($this->wards)->firstWhere('WardCode', $this->ward_id);

        $this->province_default = $customer_province;
        $this->district_default = $customer_district;
        $this->ward_default = $customer_ward;
    }

    #[On('updateProvince')]
    public function getDistrict(GhnService $ghn)
    {
        $this->ward_id = null;
        $this->district_id = null;
        $this->districts = $ghn->getDistricts($this->province_id);
        $this->modal_open = true;
    }

    #[On('updateDistrict')]
    public function getWard(GhnService $ghn)
    {
        $this->ward_id = null;
        $this->wards = $ghn->getWards($this->district_id);
        $this->modal_open = true;
    }
    public function render()
    {
        return view('detailmodule::livewire.components.shipment-calculate');
    }
}
