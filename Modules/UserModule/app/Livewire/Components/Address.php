<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use App\Services\GhnService;
use App\Models\CheckoutAddress;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;

class Address extends Component
{
    public $customer_name, $phone, $address;
    public $province_id, $district_id, $ward_id;
    public $address_type, $address_default = false;

    public $provinces = [], $districts = [], $wards = [];
    public $addresses;

    public $showModal = false;
    public $showUpdateModal = false;

    public $addressToUpdate;
    public $update_name, $update_phone, $update_province_id, $update_district_id, $update_ward_id, $update_detail;
    public $update_address_type, $update_address_default;

    public $showDeleteModal = false;
    public $addressToDelete;


    public function mount(GhnService $ghn)
    {
        $this->provinces = Cache::remember('provinces', 3600, function () use ($ghn) {
            return $ghn->getProvinces();
        });

        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset([
            'customer_name',
            'phone',
            'address',
            'province_id',
            'district_id',
            'ward_id',
            'districts',
            'wards',
            'address_type',
            'address_default'
        ]);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveAddress()
    {
        $this->validate([
            'customer_name' => 'required|string|max:125',
            'phone' => 'required|regex:/^(\d{10})$/',
            'address' => 'required|string',
            'address_type' => 'required',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
        ]);

        $province = Province::where('province_code', $this->province_id)->first();
        $district = District::where('district_code', $this->district_id)->first();
        $ward = Ward::where('ward_code', $this->ward_id)->first();

        CheckoutAddress::create([
            'user_id' => Auth::id(),
            'customer_name' => $this->customer_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_id' => $province?->id,
            'district_id' => $district?->id,
            'ward_id' => $ward?->id,
            'province_name' => $province?->name ?? '',
            'district_name' => $district?->name ?? '',
            'ward_name' => $ward?->name ?? '',
            'address_type' => $this->address_type,
            'address_default' => $this->address_default,
        ]);

        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();

        $this->closeModal();
        session()->flash('message', 'Đã lưu địa chỉ thành công!');
    }
    #[On('updateProvince')]

    public function updatedProvinceId($value)
    {
        $this->province_id = (int) $value;

        $ghn = app(GhnService::class);

        $this->districts = Cache::remember("districts_of_province_{$this->province_id}", 3600, function () use ($ghn) {
            return $ghn->getDistricts($this->province_id);
        });

        $this->district_id = null;
        $this->wards = [];
        $this->ward_id = null;
    }
    #[On('updateDistrict')]
    public function updatedDistrictId($value)
    {
        $this->district_id = (int) $value;

        $ghn = app(GhnService::class);

        $this->wards = Cache::remember("wards_of_district_{$this->district_id}", 3600, function () use ($ghn) {
            return $ghn->getWards($this->district_id);
        });

        $this->ward_id = null;
    }

    public function openUpdateModal($id)
    {
        $this->resetValidation();

        $this->addressToUpdate = CheckoutAddress::findOrFail($id);

        $this->update_name = $this->addressToUpdate->customer_name;
        $this->update_phone = $this->addressToUpdate->phone;
        $this->update_detail = $this->addressToUpdate->address;
        $this->update_province_id = $this->addressToUpdate->province_id;
        $this->update_district_id = $this->addressToUpdate->district_id;
        $this->update_ward_id = $this->addressToUpdate->ward_id;
        $this->update_address_type = $this->addressToUpdate->address_type;
        $this->update_address_default = $this->addressToUpdate->address_default;

        $ghn = app(GhnService::class);
        $this->districts = Cache::remember("districts_of_province_{$this->update_province_id}", 3600, fn() => $ghn->getDistricts($this->update_province_id));
        $this->wards = Cache::remember("wards_of_district_{$this->update_district_id}", 3600, fn() => $ghn->getWards($this->update_district_id));

        $this->showUpdateModal = true;
    }

    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
    }

    public function updateAddress()
    {
        $this->validate([
            'update_name' => 'required|string|max:125',
            'update_phone' => 'required|regex:/^(\d{10})$/',
            'update_province_id' => 'required|integer',
            'update_district_id' => 'required|integer',
            'update_ward_id' => 'required|integer',
            'update_detail' => 'required|string',
        ]);

        $province = Province::find($this->update_province_id);
        $district = District::find($this->update_district_id);
        $ward = Ward::find($this->update_ward_id);

        $this->addressToUpdate->update([
            'customer_name' => $this->update_name,
            'phone' => $this->update_phone,
            'province_id' => $province?->id,
            'district_id' => $district?->id,
            'ward_id' => $ward?->id,
            'province_name' => $province?->name ?? '',
            'district_name' => $district?->name ?? '',
            'ward_name' => $ward?->name ?? '',
            'address' => $this->update_detail,
            'address_type' => $this->update_address_type,
            'address_default' => $this->update_address_default,
        ]);

        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();

        $this->closeUpdateModal();
        session()->flash('success', 'Cập nhật địa chỉ thành công!');
    }
    //xoa dia chi 
    public function confirmDelete($id)
    {
        $this->addressToDelete = $id;
        $this->showDeleteModal = true;
    }
    public function deleteAddress()
    {
        $address = CheckoutAddress::findOrFail($this->addressToDelete);

        if ($address->user_id !== Auth::id()) {
            abort(403, 'Không có quyền xoá địa chỉ này.');
        }

        $address->delete();
        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
        $this->showDeleteModal = false;
        session()->flash('success', 'Xóa địa chỉ thành công!');
    }

    //set mac dinh
    public function setDefault($id)
    {
        $address = CheckoutAddress::findOrFail($id);

        if ($address->user_id !== Auth::id()) {
            abort(403, 'Không có quyền cập nhật địa chỉ này.');
        }

        // Bỏ mặc định tất cả địa chỉ khác
        CheckoutAddress::where('user_id', Auth::id())
            ->where('id', '!=', $id)
            ->update(['address_default' => false]);

        // Đặt mặc định địa chỉ hiện tại
        $address->address_default = true;
        $address->save();

        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
        session()->flash('success', 'Cập nhật địa chỉ mặc định thành công!');
    }


    public function render()
    {
        return view('usermodule::livewire.components.address');
    }
}
