<?php

namespace Modules\PaymentModule\Livewire\Components;

use Illuminate\Support\Facades\Log;

use Livewire\Component;
use App\Services\GhnService;
use Livewire\Attributes\On;


class Address extends Component
{

    public $province_id;
    public $district_id;
    public $ward_id;
    public $address;
    public $provinces = [];
    public $districts = [];
    public $wards = [];

    public $customer_name;
    public $phone;
    public $contact_email;

    public $isConfirmed = false;

    public function mount()
    {
        $this->provinces = app(GhnService::class)->getProvinces();

        $shipping = session('shipping_address');
        if ($shipping) {
            $this->customer_name = $shipping['customer_name'] ?? null;
            $this->phone = $shipping['phone'] ?? null;
            $this->contact_email = $shipping['contact_email'] ?? null;
            $this->address = $shipping['address'] ?? null;
            $this->province_id = $shipping['province_id'] ?? null;
            $this->district_id = $shipping['district_id'] ?? null;
            $this->ward_id = $shipping['ward_id'] ?? null;

            if ($this->province_id) {
                $this->districts = app(GhnService::class)->getDistricts($this->province_id);
            }
            if ($this->district_id) {
                $this->wards = app(GhnService::class)->getWards($this->district_id);
            }
        }
    }


    #[On('updateProvince')]
    public function updatedProvinceId()
    {
        $ghn = app(GhnService::class);
        $this->districts = $ghn->getDistricts($this->province_id);
        $this->district_id = null;
        $this->wards = [];
        $this->ward_id = null;
    }

    #[On('updateDistrict')]
    public function updatedDistrictId()
    {
        $ghn = app(GhnService::class);
        $this->wards = $ghn->getWards($this->district_id);
        $this->ward_id = null;

        Log::info('📦 Wards fetched from GHN:', $this->wards);
    }

    public function submitAddress()
    {
        $this->validate([
            'address' => 'required|string',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
        ], [
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành.',
            'district_id.required' => 'Vui lòng chọn quận/huyện.',
            'ward_id.required' => 'Vui lòng chọn phường/xã.',
        ]);
        Log::info('📝 Submitting address:', [
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
        ]);

        Log::info('🔎 Checking wards before matching:', $this->wards);

        $ward = collect($this->wards)->firstWhere('WardCode', (string)$this->ward_id);
        $district = collect($this->districts)->firstWhere('DistrictID', $this->district_id);
        $province = collect($this->provinces)->firstWhere('ProvinceID', $this->province_id);

        Log::info('✅ Matched ward:', $ward);
        Log::info('✅ Matched district:', $district);
        Log::info('✅ Matched province:', $province);

        $fullAddress = "{$this->address}, {$ward['WardName']}, {$district['DistrictName']}, {$province['ProvinceName']}";

        session()->put('shipping_address', [
            'customer_name'  => $this->customer_name,
            'phone'          => $this->phone,
            'contact_email'  => $this->contact_email,
            'province_id'    => $this->province_id,
            'province_name'  => collect($this->provinces)->firstWhere('ProvinceID', $this->province_id)['ProvinceName'] ?? null,
            'district_id'    => $this->district_id,
            'district_name'  => collect($this->districts)->firstWhere('DistrictID', $this->district_id)['DistrictName'] ?? null,
            'ward_id'        => $this->ward_id,
            'ward_name'      => collect($this->wards)->firstWhere('WardCode', (string)$this->ward_id)['WardName'] ?? null,
            'address'        => $this->address,
            'full_address'   => $this->getFullAddressProperty(),
        ]);
        $this->dispatch('addressSaved');
        $this->mount();


        $this->dispatch('toast', type: 'success', message: 'Đã chọn địa chỉ giao hàng..');
    }
    public function canSubmit()
    {
        return $this->customer_name && $this->phone && $this->address &&
            $this->province_id && $this->district_id && $this->ward_id;
    }

    public function getFullAddressProperty()
    {
        $province = collect($this->provinces)->firstWhere('ProvinceID', $this->province_id);
        $district = collect($this->districts)->firstWhere('DistrictID', $this->district_id);
        $ward = collect($this->wards)->firstWhere('WardCode', (string)$this->ward_id);

        Log::info('📍 Full Address Debug:', [
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
            'found_ward' => $ward,
        ]);

        $parts = array_filter([
            $this->address,
            $ward['WardName'] ?? null,
            $district['DistrictName'] ?? null,
            $province['ProvinceName'] ?? null,
        ]);

        return implode(', ', $parts);
    }


    public function confirmAddress()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
        ]);

        session()->put('shipping_address', [
            'customer_name' => $this->customer_name,
            'phone' => $this->phone,
            'contact_email' => $this->contact_email,
            'fullAddress' => $this->address,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
        ]);

        $this->isConfirmed = true;
    }
    public function render()
    {
        return view('paymentmodule::livewire.components.address');
    }
}
