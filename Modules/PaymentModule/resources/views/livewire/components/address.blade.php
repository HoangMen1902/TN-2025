<div>

    @php
    
        $shipping = session('shipping_address');
    @endphp



    @if ($addresses->count() > 0 )
        {{-- Hiển thị danh sách địa chỉ --}}
        <ul class="space-y-3">
            @foreach ($addresses as $address)
                <li class="relative">
                    <label class="flex border p-4 rounded-lg items-start gap-4 relative">
                        <input type="radio" name="selected_address" wire:model.live="selectedAddressId"
                            value="{{ $address->id }}" />


                        <div>
                            <p class="w-150"><strong>{{ $address->customer_name}} | {{$address->phone}} | {{$address->address}}  {{$address->ward->name}}  {{$address->district->name}}  {{$address->province->name }}</strong></p>
                            
                        </div>

                        {{-- Nút xoá --}}
                        <button type="button" wire:click="confirmDelete({{ $address->id }})"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            Xóa
                        </button>
                    </label>
                </li>
            @endforeach


        </ul>
        <div
            class="fixed {{$showDeleteModal ? '' : 'hidden'}} inset-0 z-50  bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
                <h2 class="text-lg font-semibold text-center mb-4">Xác nhận xóa</h2>
                <p class="text-center text-gray-700 mb-6">Bạn có chắc chắn muốn xóa địa chỉ này không?</p>
                <div class="flex justify-center gap-4">
                    <button type="button" wire:click="deleteAddress"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded font-medium">
                        Xóa
                    </button>
                    <button type="button" wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 border border-gray-300 hover:bg-gray-100 text-gray-700 rounded font-medium">
                        Hủy
                    </button>
                </div>
            </div>
        </div>

        {{-- Nút hiển thị modal nhập địa chỉ mới --}}
        <div class="mt-4">
            <button type="button" wire:click="$set('showModal', true)"
                class="text-blue-600 hover:underline text-sm font-medium mt-2">
                + Nhập địa chỉ mới
            </button>
        </div>
    @else
        <div>
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
                            <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Nhà
                                riêng</span>
                        </label>
                        <label>
                            <input type="radio" wire:model="address_type" value="office" class="hidden peer">
                            <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Văn
                                phòng</span>
                        </label>
                    </div>
                    @error('address_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-4 md:mb-6">
                    <input type="checkbox" wire:model="address_default" id="defaultAddress"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc
                        định</label>
                </div>

                <div class="flex gap-3 md:gap-4">
                    <button type="button" wire:click="closeModal"
                        class="flex-1 py-2 border border-blue-500 hover:bg-blue-500 hover:text-white rounded font-medium text-blue-500 text-sm md:text-base">Trở
                        Lại</button>
                    <button type="submit"
                        class="flex-1 py-2 bg-blue-500 hover:bg-blue-600 rounded font-medium text-white text-sm md:text-base">Hoàn
                        thành</button>
                </div>
            </form>
        </div>

        {{-- <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_name">Họ và tên</label>
                <input id="customer_name" name="customer_name" wire:model="customer_name" type="text" required
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="Ex: Nguyễn Văn A, ...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Số điện thoại</label>
                <input id="phone" name="phone" type="text" wire:model="phone" required
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="0123 456 789">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_email">Email liên hệ (không bắt
                    buộc)</label>
                <input id="contact_email" name="contact_email" wire:model="contact_email" type="email"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="email@example.com">
            </div>

            <div class="my-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Địa chỉ chi tiết</label>
                <input id="address" name="address" type="text" wire:model="address" required
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
            </div>

            <button wire:click="submitAddress" type="button"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg mt-4">
                Xác nhận địa chỉ
            </button>
        </div> --}}
    @endif

    {{-- Hiển thị modal nếu người dùng muốn nhập địa chỉ mới --}}
    @if ($showModal)
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
                                    <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Nhà
                                        riêng</span>
                                </label>
                                <label>
                                    <input type="radio" wire:model="address_type" value="office" class="hidden peer">
                                    <span class="px-3 py-2 border rounded cursor-pointer peer-checked:bg-blue-100">Văn
                                        phòng</span>
                                </label>
                            </div>
                            @error('address_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center mb-4 md:mb-6">
                            <input type="checkbox" wire:model="address_default" id="defaultAddress"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc
                                định</label>
                        </div>

                        <div class="flex gap-3 md:gap-4">
                            <button type="button" wire:click="closeModal"
                                class="flex-1 py-2 border border-blue-500 hover:bg-blue-500 hover:text-white rounded font-medium text-blue-500 text-sm md:text-base">Trở
                                Lại</button>
                            <button type="submit"
                                class="flex-1 py-2 bg-blue-500 hover:bg-blue-600 rounded font-medium text-white text-sm md:text-base">Hoàn
                                thành</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
</div>