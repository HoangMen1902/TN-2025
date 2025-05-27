<div>
    <div class="flex flex-col md:flex-row w-full">
        <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
            <x-usermodule::sidebar></x-usermodule::sidebar>
        </div>
        <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
            <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                <h1 class="text-lg md:text-2xl font-medium">Địa chỉ của tôi</h1>
                <button wire:click="openModal"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 md:px-4 md:py-2 rounded flex items-center text-sm md:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden xs:inline">Thêm địa chỉ mới</span>
                    <span class="xs:hidden">Thêm</span>
                </button>
            </div>
            <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">
            <div class="mb-6">
                <h2 class="text-base p-4">Địa chỉ</h2>

                @foreach ($addresses as $address)
                <div class="bg-white rounded shadow-sm mb-4 p-3 md:p-4 mx-3 md:mx-4">
                    <div class="flex flex-col md:flex-row md:justify-between">
                        <div>
                            <div class="flex flex-col md:flex-row md:items-center md:gap-2 mb-1">
                                <span class="font-medium">{{ $address->customer_name }}</span>
                                <span class="hidden md:inline text-gray-500">|</span>
                                <span class="text-gray-500">{{ $address->phone }}</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-1">
                                {{ $address->address }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                {{ $address->ward->name }}, {{ $address->district->name }}, {{ $address->province->name }}

                            </p>
                            @if($address->address_default)
                            <div class="mt-2">
                                <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5 rounded">Mặc định</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex flex-col md:flex-col mt-3 md:mt-0">
                            <div class="flex gap-4 mb-2">
                                <button wire:click="openUpdateModal({{ $address->id }})" class="text-blue-500 cursor-pointer hover:underline">
                                    Cập nhật
                                </button>
                                <button wire:click="confirmDelete({{ $address->id }})"
                                    class="text-red-500 hover:text-red-700">
                                    Xóa
                                </button>


                            </div>
                            @if (!$address->address_default)
                            <div>
                                <button
                                    wire:click="setDefault({{ $address->id }})"
                                    class="border border-gray-300 px-2 py-1 rounded text-xs">
                                    Thiết lập mặc định
                                </button>
                            </div>
                            @endif


                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="fixed {{$showDeleteModal ? '' : 'hidden'}} inset-0 z-50  bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
                    <h2 class="text-lg font-semibold text-center mb-4">Xác nhận xóa</h2>
                    <p class="text-center text-gray-700 mb-6">Bạn có chắc chắn muốn xóa địa chỉ này không?</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="deleteAddress"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded font-medium">
                            Xóa
                        </button>
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100 text-gray-700 rounded font-medium">
                            Hủy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Form Cập nhật -->
            <div class="fixed {{$showUpdateModal ? '' : 'hidden'}} inset-0 bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-300">
                    <div class="p-4 md:p-6">
                        <div class="flex items-center justify-between mb-4 md:mb-6">
                            <h2 class="text-lg md:text-xl font-medium text-center w-full">Cập nhật địa chỉ</h2>
                            <button type="button" wire:click="closeUpdateModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="updateAddress">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-1">
                                <div>
                                    <input type="text" wire:model="update_name" placeholder="Họ và tên"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('update_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="tel" wire:model="update_phone" placeholder="Số điện thoại"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('update_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Tỉnh/Thành phố</label>
                                <select wire:model="update_province_id" wire:change="getDistrict('update')"
                                    class="w-full border border-gray-300 p-2 rounded-md">
                                    <option value="">-- Chọn tỉnh/thành --</option>
                                    @foreach ($provinces as $province)
                                    <option value="{{ $province['ProvinceID'] }}">{{ $province['ProvinceName'] }}</option>
                                    @endforeach
                                </select>
                                @error('update_province_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Quận/Huyện</label>
                                <select wire:model="update_district_id" wire:change="getWard('update')"
                                    class="w-full border border-gray-300 p-2 rounded-md">
                                    <option value="">-- Chọn quận/huyện --</option>
                                    @foreach ($districts as $district)
                                    <option value="{{ $district['DistrictID'] }}">{{ $district['DistrictName'] }}</option>
                                    @endforeach
                                </select>
                                @error('update_district_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Phường/Xã</label>
                                <select wire:model="update_ward_id" class="w-full border border-gray-300 p-2 rounded-md">
                                    <option value="">-- Chọn phường/xã --</option>
                                    @foreach ($wards as $ward)
                                    <option value="{{ $ward['WardCode'] }}">{{ $ward['WardName'] }}</option>
                                    @endforeach
                                </select>
                                @error('update_ward_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 md:mb-6">
                                <textarea wire:model="update_addresses" placeholder="Địa chỉ cụ thể"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 md:h-24"></textarea>
                                @error('update_addresses')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 md:mb-6">
                                <p class="text-sm mb-2">Loại địa chỉ:</p>
                                <div class="flex gap-3 md:gap-4">
                                    <label>
                                        <input type="radio" wire:model="update_address_type" value="home" class="hidden peer">
                                        <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Nhà riêng</span>
                                    </label>
                                    <label>
                                        <input type="radio" wire:model="update_address_type" value="office" class="hidden peer">
                                        <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Văn phòng</span>
                                    </label>
                                </div>
                                @error('update_address_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex gap-3 md:gap-4">
                                <button type="button"
                                    wire:click="closeUpdateModal"
                                    class="flex-1 py-2 border border-blue-500 hover:bg-blue-500 hover:text-white rounded font-medium text-blue-500 text-sm md:text-base">Trở Lại</button>
                                <button type="submit"
                                    class="flex-1 py-2 bg-blue-500 hover:bg-blue-600 rounded font-medium text-white text-sm md:text-base">Cập nhật</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed inset-0  {{$showModal ? '' : 'hidden'}}  bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-300">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h2 class="text-lg md:text-xl font-medium text-center w-full">Thêm địa chỉ mới</h2>
                    <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveAddress">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-1">
                        <div>
                            <input type="text" wire:model="customer_name" placeholder="Họ và tên"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('customer_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="tel" wire:model="phone" placeholder="Số điện thoại"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Tỉnh/Thành phố</label>
                        <select wire:model="province_id" wire:change="getDistrict('create')"
                            class="w-full border border-gray-300 p-2 rounded-md">
                            <option value="">-- Chọn tỉnh/thành --</option>
                            @foreach ($provinces as $province)
                            <option value="{{ $province['ProvinceID'] }}">{{ $province['ProvinceName'] }}</option>
                            @endforeach
                        </select>
                        @error('province_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Quận/Huyện</label>
                        <select wire:model="district_id" wire:change="getWard('create')"
                            class="w-full border border-gray-300 p-2 rounded-md">
                            <option value="">-- Chọn quận/huyện --</option>
                            @foreach ($districts as $district)
                            <option value="{{ $district['DistrictID'] }}">{{ $district['DistrictName'] }}</option>
                            @endforeach
                        </select>
                        @error('district_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>



                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Phường/Xã</label>
                        <select wire:model="ward_id" class="w-full border border-gray-300 p-2 rounded-md">
                            <option value="">-- Chọn phường/xã --</option>
                            @foreach ($wards as $ward)
                            <option value="{{ $ward['WardCode'] }}">{{ $ward['WardName'] }}</option>
                            @endforeach
                        </select>
                        @error('ward_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="mb-4 md:mb-6">
                        <textarea wire:model="address" placeholder="Địa chỉ cụ thể"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 md:h-24"></textarea>
                        @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 md:mb-6">
                        <p class="text-sm mb-2">Loại địa chỉ:</p>
                        <div class="flex gap-3 md:gap-4">
                            <label>
                                <input type="radio" wire:model="address_type" value="home" class="hidden peer">
                                <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Nhà riêng</span>
                            </label>
                            <label>
                                <input type="radio" wire:model="address_type" value="office" class="hidden peer">
                                <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Văn phòng</span>
                            </label>
                        </div>
                        @error('address_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center mb-4 md:mb-6">
                        <input type="checkbox" wire:model="address_default" id="defaultAddress"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <div class="flex gap-3 md:gap-4">
                        <button type="button"
                            wire:click="closeModal"
                            class="flex-1 py-2 border border-blue-500 hover:bg-blue-500 hover:text-white rounded font-medium text-blue-500 text-sm md:text-base">Trở Lại</button>
                        <button type="submit"
                            class="flex-1 py-2 bg-blue-500 hover:bg-blue-600 rounded font-medium text-white text-sm md:text-base">Hoàn thành</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>