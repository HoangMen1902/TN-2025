<div class="">
    @php
        $shipping = session('shipping_address');
    @endphp
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_name">Họ và
                tên</label>
            <input id="customer_name" name="customer_name" wire:model="customer_name"
                 type="text" required
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                placeholder="Ex: Nguyễn Văn A, ...">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Số điện
                thoại</label>
            <input id="phone" name="phone" type="text"  wire:model="phone" required
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                placeholder="0123 456 789">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_email">Email liên
                hệ (không bắt buộc)</label>
            <input id="contact_email" name="contact_email" wire:model="contact_email"
                type="email" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                placeholder="email@example.com">
        </div>
    </div>
    <div class="my-4">
        <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Địa chỉ chi
            tiết</label>
        <input id="address" name="address" type="text"  wire:model="address" required
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
            placeholder="123 Nguyễn Văn Linh, ..." />
        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Chọn tỉnh/thành</label>
            <select wire:model="province_id" id="province" class="w-full p-2 border rounded-lg">
                <option value="">-- Tỉnh/Thành --</option>
                @foreach($provinces as $province)
                    <option value="{{ $province['ProvinceID'] }}">{{ $province['ProvinceName'] }}</option>
                @endforeach
            </select>
            @error('province_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Chọn quận/huyện</label>
            <select wire:model="district_id" id="district" class="w-full p-2 border rounded-lg" @if(!$districts)
            disabled @endif>
                <option value="">-- Quận/Huyện --</option>
                @foreach($districts as $district)
                    <option value="{{ $district['DistrictID'] }}">{{ $district['DistrictName'] }}</option>
                @endforeach
            </select>
            @error('district_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Chọn phường/xã</label>
            <select wire:model="ward_id" class="w-full p-2 border rounded-lg" @if(!$wards) disabled @endif>
                <option value="">-- Phường/Xã --</option>
                @foreach($wards as $ward)
                    <option value="{{ $ward['WardCode'] }}">{{ $ward['WardName'] }}</option>
                @endforeach
            </select>
            @error('ward_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>


        <button wire:click="submitAddress" type="button"
            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg mt-4">
            Xác nhận địa chỉ
        </button>





    </div>

</div>