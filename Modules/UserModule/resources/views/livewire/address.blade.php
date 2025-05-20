<form wire:submit.prevent="saveAddress">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4">
        <input type="text" wire:model="customer_name" placeholder="Họ và tên"
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="tel" wire:model="phone" placeholder="Số điện thoại"
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="mb-4">
        <div class="relative">
            <select wire:model="province_id" id="province"
                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Tỉnh/Thành phố</option>
                @foreach ($provinces as $province)
                <option value="{{ $province['ProvinceID'] }}">{{ $province['ProvinceName'] }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="relative">
            <select wire:model="district_id"
                id="district"
                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Quận/Huyện</option>
                @foreach ($districts as $district)
                <option value="{{ $district['DistrictID'] }}">{{ $district['DistrictName'] }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="relative">
            <select wire:model="ward_id" id="ward"
                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Phường/Xã</option>
                @foreach ($wards as $ward)
                <option value="{{ $ward['WardCode'] }}">{{ $ward['WardName'] }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <div class="mb-4 md:mb-6">
        <textarea wire:model="address" placeholder="Địa chỉ cụ thể"
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 md:h-24"></textarea>
    </div>

    <div class="mb-4 md:mb-6">
        <p class="text-sm mb-2">Loại địa chỉ:</p>
        <div class="flex gap-3 md:gap-4">
            <label>
                <input type="radio" wire:model="address_type" value="home"
                    class="hidden peer">
                <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Nhà riêng</span>
            </label>
            <label>
                <input type="radio" wire:model="address_type" value="office"
                    class="hidden peer">
                <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Văn phòng</span>
            </label>
        </div>
    </div>

    <div class="flex items-center mb-4 md:mb-6">
        <input type="checkbox" wire:model="address_default" id="defaultAddress"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
        <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc định</label>
    </div>

    <div class="flex gap-3 md:gap-4">
        <button type="button"
            class="flex-1 py-2 border border-red-500 rounded font-medium text-red-500 text-sm md:text-base">Trở Lại</button>
        <button type="submit"
            class="flex-1 py-2 bg-red-500 hover:bg-red-600 rounded font-medium text-white text-sm md:text-base">Hoàn thành</button>
    </div>
              </div>
            </div>
        </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('province').addEventListener('change', function() {
                Livewire.dispatch('updateProvince');
            });

            document.getElementById('district').addEventListener('change', function() {
                Livewire.dispatch('updateDistrict');
            });
        });
        
    </script>
</form>