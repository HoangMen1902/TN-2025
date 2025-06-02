<?php

namespace Modules\PaymentModule\Livewire\Components;

use Illuminate\Support\Facades\Log;

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

    public $customer_name;
    public $phone;
    public $address;
    public $province_id;
    public $district_id;
    public $ward_id;
    public $address_type;
    public $address_default = false;

    public $provinces = [];
    public $districts = [];
    public $wards = [];
    public $addresses;

    public $showModal = false;
    public $showUpdateModal = false;
    public $showDeleteModal = false;

    public $addressToUpdate;
    public $update_name;
    public $update_phone;
    public $update_province_id;
    public $update_district_id;
    public $update_ward_id;
    public $update_addresses;
    public $update_address_type;
    public $contact_email;
    public $addressToDelete;
    public $selectedAddressId = null;

    public $isConfirmed = false;

    public function updatedSelectedAddressId($id)
    {
        $address = CheckoutAddress::where('user_id', Auth::id())->find($id);

        if ($address) {
            session([
                'shipping_address' => [
                    'full_address' => $address->address,
                    'phone' => $address->phone,
                    'contact_email' => $address->contact_email,
                    'customer_name' => $address->customer_name,
                ],
                'selected_address_id' => $address->id,
            ]);

            $this->customer_name = $address->customer_name;
            $this->phone = $address->phone;
            $this->contact_email = $address->contact_email;
            $this->address = $address->address;
        }
        
    }

    public function mount(GhnService $ghn)
    {
        $this->showModal = false;
        $this->showUpdateModal = false;
        $this->showDeleteModal = false;
        $this->provinces = $ghn->getProvinces();
        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();

        $this->provinces = app(GhnService::class)->getProvinces();
    }
    public function resetForm()
    {
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
            'phone' => 'required|regex:/^\d{10}$/',
            'address' => 'required|string',
            'address_type' => 'required',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
        ], [
            'customer_name.required' => 'Vui lòng nhập tên người nhận.',
            'customer_name.max' => 'Tên người nhận không được vượt quá 125 ký tự.',

            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Phải gồm 10 chữ số.',

            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',

            'address_type.required' => 'Vui lòng chọn loại địa chỉ.',

            'province_id.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'province_id.integer' => 'Tỉnh/Thành phố không hợp lệ.',

            'district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'district_id.integer' => 'Quận/Huyện không hợp lệ.',

            'ward_id.required' => 'Vui lòng chọn Phường/Xã.',
            'ward_id.integer' => 'Phường/Xã không hợp lệ.',
        ]);

        if ($this->address_default) {
            CheckoutAddress::where('user_id', Auth::id())
                ->update(['address_default' => false]);
        }


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
        $this->dispatch('toast', type: 'success', message: 'Đã lưu địa chỉ thành công!');

    
    }
    #[On('updateProvince')]
    public function getDistrict($mode = 'create', GhnService $ghn)
    {
        $provinceId = $mode === 'update' ? $this->update_province_id : $this->province_id;
        $this->districts = $ghn->getDistricts($provinceId);
        if ($mode === 'update') {
            $this->update_district_id = null;
            $this->update_ward_id = null;
        } else {
            $this->district_id = null;
            $this->ward_id = null;
        }

        $this->wards = [];
    }


    #[On('updateDistrict')]
    public function getWard($mode = 'create', GhnService $ghn)
    {
        $districtId = $mode === 'update' ? $this->update_district_id : $this->district_id;
        $this->wards = $ghn->getWards($districtId);

        if ($mode === 'update') {
            $this->update_ward_id = null;
        } else {
            $this->ward_id = null;
        }
    }



    public function openUpdateModal($id)
    {
        $this->resetValidation();

        $this->addressToUpdate = CheckoutAddress::findOrFail($id);

        $this->update_name = $this->addressToUpdate->customer_name;
        $this->update_phone = $this->addressToUpdate->phone;
        $this->update_addresses = $this->addressToUpdate->address;
        $this->update_address_type = $this->addressToUpdate->address_type;

        $province = Province::find($this->addressToUpdate->province_id);
        $district = District::find($this->addressToUpdate->district_id);
        $ward = Ward::find($this->addressToUpdate->ward_id);

        $this->update_province_id = $province?->province_code;
        $this->update_district_id = $district?->district_code;
        $this->update_ward_id     = $ward?->ward_code;

        // Gọi hàm để load danh sách quận & xã theo tỉnh đang có
        $ghn = app(GhnService::class);
        $this->districts = $ghn->getDistricts($this->update_province_id);
        $this->wards = $ghn->getWards($this->update_district_id);

        $this->showUpdateModal = true;
    }


    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
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
        $this->resetForm();



        $this->dispatch('toast', type: 'success', message: 'Đã chọn địa chỉ giao hàng..');
    }
    public function updateAddress()
    {
        $this->validate([
            'update_name' => 'required|string|max:125',
            'update_phone' => 'required|regex:/^\d{10}$/',
            'update_addresses' => 'required|string',
            'update_address_type' => 'required',
            'update_province_id' => 'required|integer',
            'update_district_id' => 'required|integer',
            'update_ward_id' => 'required|integer',
        ], [
            'update_name.required' => 'Vui lòng nhập tên người nhận.',
            'update_name.max' => 'Tên người nhận không được vượt quá 125 ký tự.',

            'update_phone.required' => 'Vui lòng nhập số điện thoại.',
            'update_phone.regex' => 'Số điện thoại không hợp lệ. Phải gồm 10 chữ số.',

            'update_addresses.required' => 'Vui lòng nhập địa chỉ chi tiết.',

            'update_address_type.required' => 'Vui lòng chọn loại địa chỉ.',

            'update_province_id.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'update_province_id.integer' => 'Tỉnh/Thành phố không hợp lệ.',

            'update_district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'update_district_id.integer' => 'Quận/Huyện không hợp lệ.',

            'update_ward_id.required' => 'Vui lòng chọn Phường/Xã.',
            'update_ward_id.integer' => 'Phường/Xã không hợp lệ.',
        ]);


        $province = Province::where('province_code', $this->update_province_id)->first();
        $district = District::where('district_code', $this->update_district_id)->first();
        $ward     = Ward::where('ward_code', $this->update_ward_id)->first();

        if (!$province || !$district || !$ward) {
            session()->flash('error', 'Không thể cập nhật địa chỉ. Vui lòng chọn lại Tỉnh/Quận/Xã.');
            return;
        }

        $this->addressToUpdate->update([
            'customer_name'   => $this->update_name,
            'phone'           => $this->update_phone,
            'province_id'     => $province->id,
            'district_id'     => $district->id,
            'ward_id'         => $ward->id,
            'province_name'   => $province->name,
            'district_name'   => $district->name,
            'ward_name'       => $ward->name,
            'address'         => $this->update_addresses,
            'address_type'    => $this->update_address_type,
        ]);

        $this->closeUpdateModal();
        $this->addresses = CheckoutAddress::where('user_id', Auth::id())->get();
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

        return view('paymentmodule::livewire.components.address',);
    }
}
