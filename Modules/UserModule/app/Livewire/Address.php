<?php

namespace Modules\UserModule\Livewire;

use Livewire\Component;
use App\Services\GhnService;
use App\Models\CheckoutAddress;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Address extends Component
{
    public $customer_name;
    public $phone;
    public $address;

    public $address_type;

    public $address_default = false;
    public $province_id;
    public $district_id;
    public $ward_id;
    public $provinces = [];
    public $districts = [];
    public $wards = [];
    public $addresses;

    public function mount(GhnService $ghn)
    {
        $this->provinces = $ghn->getProvinces();
        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
    }

    public function saveAddress(GhnService $ghn)
    {
        $this->validate([
            'customer_name' => 'required|string|max:125',
            'phone' => 'required|string|regex:/^(\d{10})$/',
            'address' => 'required|string',
            'address_type' => 'required',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'customer_name.max' => 'Họ tên không được vượt quá 125 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'province_id.integer' => 'Tỉnh/thành phố không hợp lệ.',
            'district_id.required' => 'Vui lòng chọn quận/huyện.',
            'district_id.integer' => 'Quận/huyện không hợp lệ.',
            'ward_id.required' => 'Vui lòng chọn phường/xã.',
            'ward_id.integer' => 'Phường/xã không hợp lệ.',
            'address_type' => 'Vui lòng chọn loại địa chỉ.',
        ]);

        $provinceModel = Province::where('province_code', $this->province_id)->first();
        $districtModel = District::where('district_code', $this->district_id)->first();
        $wardModel = Ward::where('ward_code', $this->ward_id)->first();

        CheckoutAddress::create([
            'user_id' => Auth::id(),
            'customer_name' => $this->customer_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_name' => $provinceModel->name,
            'district_name' => $districtModel->name,
            'ward_name' => $wardModel->name,
            'province_id' => $provinceModel->id,
            'district_id' => $districtModel->id,
            'ward_id' => $wardModel->id,
            'address_default' => $this->address_default,
            'address_type' => $this->address_type
        ]);


        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();

        // Đặt lại các giá trị form
        $this->reset(['customer_name', 'phone', 'address', 'province_id', 'district_id', 'ward_id', 'address_type', 'address_default']);


        session()->flash('message', 'Success!');
        
    }

    #[On('updateProvince')]
    public function updatedProvinceId(GhnService $ghn)
    {
        $this->province_id = (int) $this->province_id;
        $districtsData = $ghn->getDistricts($this->province_id);
        $this->districts = $districtsData;
        $this->district_id = null;
        $this->wards = [];
        $this->ward_id = null;
    }

    #[On('updateDistrict')]
    public function updatedDistrictId(GhnService $ghn)
    {
        $wardsData = $ghn->getWards($this->district_id);
        $this->wards = $wardsData;
        $this->ward_id = null;
    }

    public function render()
    {
        return view('usermodule::livewire.address');
    }
}
