<div class="shipment-container lg:w-full ">
    <div class="shipment mt-3 lg:block hidden">
        <h1 class="text-lg font-bold">Thông tin vận chuyển</h1>
        <div class="shipment-wrapper">
            <div class="pt-2">
                <span class="text-sm">Giao hàng đến</span>

                <span class="font-bold text-sm">
                    {{ $ward_default['WardName'] ?? 'Chưa chọn' }},
                    {{ $district_default['DistrictName'] ?? 'Chưa chọn' }},
                    {{ $province_default['ProvinceName'] ?? 'Chưa chọn' }}
                </span>
                <a class="text-sm text-blue-500 change-address" href="javascript:void(0)">Thay đổi</a>
            </div>

            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="#2FB684" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                <div class="flex flex-col ml-3 m-2">
                    <span class="font-bold">Giao hàng tiêu chuẩn</span>
                    <span class="text-sm">Dự kiến giao <span class="font-bold">{{$estimatedTime}}</span></span>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-4">
                    <h1 class="text-lg font-bold">Ưu đãi có thể áp dụng</h1>
                    <a href="" class="text-sm text-blue-500">Xem thêm ></a>
                </div>
                <div class="coupon-related w-full mt-4">
                    <div class="coupon-scroller flex items-center overflow-x-auto w-full gap-3">
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                        <div class="coupon-container flex items-center gap-1">
                            <div class="coupon-img bg-amber-500 p-1 rounded">
                                <img src="{{asset('assets/images/coupon.png')}}" alt="">
                            </div>
                            <div class="coupon-name truncate text-[13px] font-bold">
                                Mã giảm giá 10K - cho đơn hàng 30K
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex items-center gap-12 mt-4">
                <div class="flex flex-col gap-4 justify-between">
                    @if ($type === "product")
                        <h1>Phân loại</h1>

                    @endif
                    <h1 class="font-bold text-base">Số lượng:</h1>
                </div>


                <div class="flex flex-col gap-4 justify-between">
                    @if ($type === "product" && isset($data) && $data && $data->productSkus)
                        <div class="flex space-x-2">
                            @foreach ($data->productSkus as $sku)
                                <a href="javascript:void(0)" wire:click="selectSku({{ $sku->id }})"
                                    class="{{ isset($currentSku) && $currentSku && $currentSku->id === $sku->id ? 'select-sku-btn flex items-center px-4 py-2 rounded border border-blue-500 bg-blue-100 text-blue-700 text-sm' : 'select-sku-btn px-4 py-2 rounded border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-100' }}">
                                    @if($sku->skuValues)
                                        @foreach ($sku->skuValues as $value)
                                            {{ $value->option->name ?? '' }} {{ $value->value->value_name ?? '' }}
                                        @endforeach
                                    @endif
                                    @if (isset($currentSku) && $currentSku && $currentSku->id === $sku->id)
                                        <svg class="ml-2 w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 111.414-1.414L8.414 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @elseif ($type === "product")
                        <div class="text-gray-500 text-sm">Không có phân loại sản phẩm</div>
                    @endif
                    <div class="quantity-container w-min">
                        <button class="btn minus bg-white hover:bg-white text-gray-400">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="font-bold"
                            form="addToCart" />
                        <button class="btn plus bg-white hover:bg-white text-gray-400">+</button>
                    </div>

                </div>
            </div>


        </div>
    </div>
    <div class="general-information-wrapper p-4 lg:hidden sm:block w-full" style="width: 100%">
        <div class="flashsale flex items-center justify-between p-4">
            <div class="flashsale-time m-[4px] w-full">
                <div class="flashsale-wrapper flex items-center justify-between">
                    <img src="{{asset('assets/images/whiteFlashsale.png')}}" alt="flashsale" class="object-contain">
                    <div class="flex gap-[5px] items-center justify-center">
                        <div class="hour-box flashsale-time-box text-white text-sm">
                            01
                        </div>
                        <span class="text-white font-bold">:</span>
                        <div class="minute-box flashsale-time-box text-white text-sm">
                            30
                        </div>
                        <span class="text-white font-bold ">:</span>
                        <div class="second-box flashsale-time-box text-white text-sm">
                            05
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="price flex items-center pt-[8px]">
            <div class="main-price text-[32px]" style="color: #C92127">
                <span>99.500 đ</span>
            </div>
            <div class="old-price line-through text-sm mr-[8px] ml-[8px]" style="color: #888888">
                199.000 đ
            </div>
            <div class="discount-percent font-bold text-white flex items-center justify-center py-[4x] px-[2px]">
                -30%
            </div>
        </div>

        <div class="rating-box flex py-[8px] border-b-1 border-b-neutral-200">
            <a href="#" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>

                <span class="text-neutral-400">(0)</span>
            </a>
            <span class="text-sm mx-1 text-neutral-400">|</span>
            <span class="text-sm font-thin">Đã bán</span>
            <span class="text-sm font-bold ml-0.5">100</span>
        </div>
        <div class="flex flex-col border-b-1 border-b-neutral-200 py-2">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="#2FB684" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                <div class="flex flex-col ml-3 m-2">
                    <span class="text-sm">Dự kiến giao <span class="font-bold">{{ $estimatedTime }}</span></span>
                    <span class="font-bold text-sm"></span>
                    <a href="javascript:void(0)" class="change-address"><span
                            class="text-neutral-400 w-full text-sm flex items-center">Giao hàng đến
                            {{$ward_default['WardName']}}, {{$district_default['DistrictName']}},
                            {{$province_default['ProvinceName']}}</span></a>
                </div>
                <a href="javascript:void(0)" class="change-address ml-auto"><svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg></a>

            </div>


        </div>

        <a href="#" class="py-4 w-full text-sm flex items-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="2" stroke="red" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>
            <span class="ml-3 text-sm">Giao nhanh và uy tín - Đổi trả miễn phí toàn quốc 30 ngày - Khách Sỉ</span> <span
                class="ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </span>
        </a>


    </div>
    <div id="modalOverlay"
        class="fixed inset-0 flex {{$modal_open ? '' : 'hidden'}} items-center justify-center z-50  animate-popup"
        style="background-color: rgba(0, 0, 0, 0.247)">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <button id="closeModalBtn"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl cursor-pointer">
                &times;
            </button>

            <form wire:submit.prevent="updateTime" id="provinceForm" class="space-y-4" method="post">
                @csrf
                <h1 class="text-lg font-semibold">Chọn địa chỉ giao hàng của bạn</h1>

                <div>
                    <label class="block text-sm font-medium mb-1">Tỉnh/Thành phố</label>
                    <select class="w-full border border-gray-300 p-2 rounded-md" name="province" id="provinceName"
                        wire:model="province_id" required>
                        <option selected>-- Chọn tỉnh/thành --</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['ProvinceID'] }}" class="text-black dark:text-black">
                                {{ $province['ProvinceName'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="{{empty($districts) || is_null($districts) ? 'hidden' : 'block'}}">
                    <label class="block text-sm font-medium mb-1">Quận/Huyện</label>
                    <select class="w-full border border-gray-300 p-2 rounded-md" name="district" id="districtName"
                        wire:model="district_id" required>
                        <option selected>-- Chọn quận/huyện --</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district['DistrictID'] }}" class="text-black dark:text-black">
                                {{ $district['DistrictName'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="{{empty($wards) || is_null($wards) ? 'hidden' : 'block'}}">
                    <label class="block text-sm font-medium mb-1">Phường/Xã</label>
                    <select class="w-full border border-gray-300 p-2 rounded-md" name="ward" id="wardName"
                        wire:model="ward_id" required>
                        <option selected>-- Chọn phường/xã --</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward['WardCode'] }}" class="text-black dark:text-black">
                                {{ $ward['WardName'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="cancel-btn bg-gray-200 px-4 py-2 rounded hover:bg-gray-300"
                        id="cancelBtn">
                        Hủy
                    </button>
                    <button type="submit" class="submit-btn bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Xác nhận
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>