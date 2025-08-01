{{-- filepath: Modules/CartModule/resources/views/livewire/component/cart.blade.php --}}
<div class="">
    <form class="hidden" method="POST" action="/thanh-toan" id="addItemCheckout">
        @csrf
    </form>
    <button wire:click="deleteAll" class="text-base md:text-lg font-medium hover:underline cursor-pointer px-2 py-1">
        Xóa toàn bộ sản phẩm
    </button>
    <h3 class="text-center mt-5 md:mt-7 text-xl md:text-2xl">Giỏ hàng</h3>
    <p class="text-center mt-2 md:mt-3 text-base md:text-lg border-b-4 border-blue-600 w-40 md:w-72 mx-auto"></p>
    <div
        class="cart__content flex flex-col xl:flex-row justify-start items-start px-0 sm:px-1 md:px-2 xl:px-5 mt-3 md:mt-5 gap-1 md:gap-4">
        <div class="cart__products flex-1">
            <div class="hidden lg:flex justify-between mb-4 md:mb-7 border-b border-gray-400 text-xs md:text-base">
                <div class="w-1/7 font-bold">Chọn</div>
                <div class="w-1/7 font-bold">Hình ảnh</div>
                <div class="w-4/7 font-bold">Sản phẩm</div>
                <div class="w-1/7 font-bold">Số lượng</div>
                <div class="w-1/7 font-bold text-center">Tổng</div>
            </div>
            @if($logged_in)
                @foreach ($cartItems as $item)
                    @php $type = $item->item_type @endphp
                    @if($type === 'sku' || $type === 'combo')
                        <div class="w-full mb-3 md:mb-5">
                            <div
                                class="border-b p-1 md:p-2 flex flex-col md:flex-row border-gray-400 items-center md:items-stretch gap-1 md:gap-0">
                                <div class="max-w-[30px] inline-flex items-center w-full md:w-1/7 p-1 md:p-2 justify-center">
                                    <label class="relative flex items-center cursor-pointer"
                                        for="blue-600-{{$type === 'sku' ? $item->sku->id : $item->combo->id}}">
                                        <input type="checkbox"
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-blue-400 transition-all"
                                            id="blue-600-{{$type === 'sku' ? $item->sku->id : $item->combo->id}}" name="cart_id[]"
                                            value="{{$item->id}}" wire:model="selected_cart" form="addItemCheckout" />
                                        <span
                                            class="absolute bg-blue-600 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                                    </label>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex justify-center  lg:w-[100px] bg-gradient-to-r from-amber-500 to-blue-500 lg:min-w-[95px] ">
                                    @php
                                        $image = $type === 'sku' ? $item->sku->images[0] : $item->combo->images[0];
                                    @endphp
                                    <img class="w-16 h-auto md:w-24 lg:w-full" src="{{ asset('storage/' . $image) }}"
                                        alt="{{ $type === 'sku' ? ($item->sku->sku ?? 'SKU Image') : 'Combo Image' }}"
                                        style="object-cover; width:100%; height:100%;">
                                </div>
                                <div class="w-full md:w-4/7 p-1 md:p-2">
                                    <h2 class="text-sm md:text-base font-bold truncate max-w-100 mt-1">
                                        {{ $type === 'sku' ? ($item->sku->product->name ?? 'Tên sản phẩm') . ' - ' . ($item->sku->sku ?? '') : ($item->combo->combo_name ?? 'Tên sản phẩm') . ' - Combo' }}
                                    </h2>
                                    @if($type === 'sku')
                                    @php
                                        $flashsaleSku = $productInFlashsale->firstWhere('sku_id', $item->sku_id);
                                    @endphp

                                        @if($flashsaleSku)
                                            @php
                                                $flashsaleType = $flashsaleSku['discount_type'];
                                                $original_price = $item->sku->sale_price ?? $item->sku->price ?? 0;
                                                $discount_amount = $flashsaleSku['discount_amount'];
                                                $discounted_price = 0;

                                                if($flashsaleType === 'percent') {
                                                    $discounted_price = $original_price * (1 - $discount_amount / 100);
                                                } elseif($flashsaleType === 'specific') {
                                                    $discounted_price = $original_price * $discount_amount;
                                                } else {
                                                    $discounted_price = $original_price;
                                                }
                                            @endphp

                                            <p class="text-red-600 font-bold text-sm md:text-base">
                                                {{ number_format($discounted_price, 0, ',', '.') }}đ - Chương trình Flashsale
                                            </p>
                                        @else
                                            <p class="text-red-600 font-bold text-sm md:text-base">
                                                {{ number_format($item->sku->sale_price ?? $item->sku->price ?? 0, 0, ',', '.') }}đ
                                            </p>
                                        @endif

                                    @else
                                    <p class="text-red-600 font-bold text-sm md:text-base">
                                        {{ number_format(($item->combo->sale_price ?? 0), 0, ',', '.') }}đ
                                    </p>
                                    @endif
                                    
                                    <div class="list-none p-0 text-xs md:text-sm ">
                                        <div class="my-1 line-clamp-2">
                                            {!! $type === 'sku' ? ($item->sku->product->short_description ?? 'Không có mô tả') : ($item->combo->description ?? 'Không có mô tả') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex flex-col items-center justify-center">
                                    <div class="flex items-center ">
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="decreaseQuantity({{ $item->id }})">−</button>
                                        <span
                                            class="w-8 md:w-10 text-center text-sm md:text-base">{{ $quantities[$item->id] ?? 1 }}</span>
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="increaseQuantity({{ $item->id }})">+</button>
                                    </div>
                                    <button wire:key="cart-item-{{ $item->id }}" wire:click="removeItem({{ $item->id }})"
                                        class="mt-1 md:mt-2 text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                            stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="w-full md:w-1/7 text-center text-red-600 flex justify-center items-center text-sm md:text-base">
              {{ isset($discounted_price) && $type === 'sku' 
    ? number_format($discounted_price, 0, ',', '.') 
    : number_format(
        ($type === 'sku'
            ? ($item->sku->sale_price ?? $item->sku->price ?? 0)
            : ($item->combo->sale_price ?? 0)
        ) * ($quantities[$item->id] ?? $item->quantity ?? 1),
        0,
        ',',
        '.'
    ) 
}}
                                    <span class="text-sm">đ</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else 
            @foreach ($cartItems['sku'] as $item)
            @if (!empty($cartItems['sku']))
            <div class="w-full mb-3 md:mb-5">
                            <div
                                class="border-b p-1 md:p-2 flex flex-col md:flex-row border-gray-400 items-center md:items-stretch gap-1 md:gap-0">
                                <div class="max-w-[30px] inline-flex items-center w-full md:w-1/7 p-1 md:p-2 justify-center">
                                    <label class="relative flex items-center cursor-pointer"
                                        for="blue-600-sku-{{$item->id}}">
                                        <input type="checkbox"
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-blue-400 transition-all"
                                            id="blue-600-sku-{{$item->id}}" name="cart_sku[]"
                                            value="{{$item->id}}" wire:model="selected_cart_skus" form="addItemCheckout" />
                                        <span
                                            class="absolute bg-blue-600 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                                    </label>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex justify-center  lg:w-[100px] bg-gradient-to-r from-amber-500 to-blue-500 lg:min-w-[95px] ">
                                    @php
                                        $image = $item->images[0];
                                    @endphp
                                    <img class="w-16 h-auto md:w-24 lg:w-full" src="{{ asset('storage/' . $image) }}"
                                        alt=""
                                        style="object-cover; width:100%; height:100%;">
                                </div>
                                <div class="w-full md:w-4/7 p-1 md:p-2">
                                    <h2 class="text-sm md:text-base font-bold truncate max-w-100 mt-1">
                                        {{($item->product->name ?? 'Tên sản phẩm') . ' - ' . ($item->sku ?? '')}}
                                    </h2>
                                    @php
                                        $flashsaleSku = $productInFlashsale->firstWhere('sku_id', $item->id);
                                    @endphp

.
                                    @if($flashsaleSku)
                                        @php
                                            $flashsaleType = $flashsaleSku['discount_type'];
                                            $original_price = $item->sale_price ?? $item->price ?? 0;
                                            $discount_amount = $flashsaleSku['discount_amount'];
                                            $discounted_price = 0;

                                            if($flashsaleType === 'percent') {
                                                $discounted_price = $original_price * (1 - $discount_amount / 100);
                                            } elseif($flashsaleType === 'specific') {
                                                $discounted_price = $original_price * $discount_amount;
                                            } else {
                                                $discounted_price = $original_price;
                                            }
                                        @endphp

                                        <p class="text-red-600 font-bold text-sm md:text-base">
                                            {{ number_format($discounted_price, 0, ',', '.') }}đ - Chương trình Flashsale
                                        </p>
                                    @else
                                        <p class="text-red-600 font-bold text-sm md:text-base">
                                            {{ number_format($item->sale_price ?? $item->price ?? 0, 0, ',', '.') }}đ
                                        </p>
                                    @endif
                                    <div class="list-none p-0 text-xs md:text-sm ">
                                        <div class="my-1 line-clamp-2">
                                            {!!  ($item->product->short_description ?? 'Không có mô tả') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex flex-col items-center justify-center">
                                    <div class="flex items-center ">
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="decreaseQuantity({{ $item->id }}, 'sku')">−</button>
                                        <span
                                            class="w-8 md:w-10 text-center text-sm md:text-base">{{ $quantities['sku'][$item->id] ?? 1 }}</span>
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="increaseQuantity({{ $item->id }},'sku')">+</button>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }}, 'sku')"
                                        class="mt-1 md:mt-2 text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                            stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="w-full md:w-1/7 text-center text-red-600 flex justify-center items-center text-sm md:text-base">
                                    {{isset($discounted_price) ? number_format($discounted_price, 0, ',', '.') : number_format($item->sale_price ??  $item->price ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}
                                    <span class="text-sm">đ</span>
                                </div>
                            </div>
                        </div>
            @endif
                        
                @endforeach
                @foreach ($cartItems['combo'] as $combo)
                @if (!empty($cartItems['combo']))
                <div class="w-full mb-3 md:mb-5">
                            <div
                                class="border-b p-1 md:p-2 flex flex-col md:flex-row border-gray-400 items-center md:items-stretch gap-1 md:gap-0">
                                <div class="max-w-[30px] inline-flex items-center w-full md:w-1/7 p-1 md:p-2 justify-center">
                                    <label class="relative flex items-center cursor-pointer"
                                        for="blue-600-combo-{{$combo->id}}">
                                        <input type="checkbox"
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-blue-400 transition-all"
                                            id="blue-600-combo-{{$combo->id }}" name="cart_combo[]"
                                            value="{{$combo->id}}" wire:model="selected_cart_combos" form="addItemCheckout" />
                                        <span
                                            class="absolute bg-blue-600 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                                    </label>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex justify-center  lg:w-[100px] bg-gradient-to-r from-amber-500 to-blue-500 lg:min-w-[95px] ">
                                    @php
                                        $image = $combo->images[0];
                                    @endphp
                                    <img class="w-16 h-auto md:w-24 lg:w-full" src="{{ asset('storage/' . $image) }}"
                                        alt=""
                                        style="object-cover; width:100%; height:100%;">
                                </div>
                                <div class="w-full md:w-4/7 p-1 md:p-2">
                                    <h2 class="text-sm md:text-base font-bold truncate max-w-100 mt-1">
                                        {{($combo->combo_name ?? 'Tên sản phẩm')}}
                                    </h2>
                                    <p class="text-red-600 font-bold text-sm md:text-base">
                                        {{ number_format( ($combo->sale_price ?? $combo->originalprice ?? 0), 0, ',', '.') }}đ
                                    </p>
                                    <div class="list-none p-0 text-xs md:text-sm ">
                                        <div class="my-1 line-clamp-2">
                                            {!!  ($combo->description ?? 'Không có mô tả') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/7 p-1 md:p-2 flex flex-col items-center justify-center">
                                    <div class="flex items-center ">
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="decreaseQuantity({{ $combo->id }}, 'combo')">−</button>
                                        <span
                                            class="w-8 md:w-10 text-center text-sm md:text-base">{{ $quantities['combo'][$combo->id] ?? 1 }}</span>
                                        <button
                                            class="w-6 h-6 rounded-full border border-gray-500 text-base md:text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                            wire:click="increaseQuantity({{ $combo->id }}, 'combo')">+</button>
                                    </div>
                                    <button wire:click="removeItem({{ $combo->id }}, 'combo')"
                                        class="mt-1 md:mt-2 text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                            stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="w-full md:w-1/7 text-center text-red-600 flex justify-center items-center text-sm md:text-base">
                                    {{ number_format($combo->sale_price ??  $combo->price ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}
                                    <span class="text-sm">đ</span>
                                </div>
                            </div>
                        </div>
                @endif
                        
                @endforeach
            @endif


            @if(empty($cartItems))
                <p class="text-center text-gray-500 text-sm md:text-base">Giỏ hàng của bạn đang trống.</p>
            @endif
        </div>
        <div
            class="cart__summary w-full xl:w-1/3 xl:ml-8 mt-4 md:mt-6 xl:mt-0 p-2 md:p-5 max-h-[230px] border border-gray-300 rounded-xl text-justify self-start break-words">
            <div class="cart__summary-item flex flex-col md:flex-row md:items-center md:gap-2 mb-2 md:mb-3">
                <span class="font-medium text-base md:text-lg">Tổng phụ</span>
                <span class="text-red-700 ml-0 md:ml-2 text-base md:text-lg" wire:loading.remove>
                    {{ number_format($total_price ?? 0, 0, ',', '.') }} <span class="text-sm">đ</span>
                </span>
                <span class="text-red-700 ml-0 md:ml-2 text-base md:text-lg" wire:loading>Đang tính giá tiền..</span>
            </div>
            <div class="cart__summary-item flex flex-col md:flex-row md:items-center md:gap-2 mb-2 md:mb-3">
                <h3 class="font-bold text-lg md:text-xl text-red-700" wire:loading.remove>
                    {{ number_format($total_price ?? 0, 0, ',', '.') }} <span class="text-sm">đ</span>
                </h3>
                <h3 class="font-bold text-lg md:text-xl text-red-700 ml-0 md:ml-2" wire:loading>Đang tính giá tiền..
                </h3>
            </div>
            <p class="text-xs md:text-sm text-gray-600">Phí ship sẽ được tính khi thanh toán</p>
            <div class="mt-3 md:mt-5 flex justify-start">
                <button
                    class="bg-primary text-white font-bold py-2 px-4 rounded-xl transition duration-300 hover:bg-white cursor-pointer text-base md:text-lg"
                    form="addItemCheckout">Thanh toán</button>
            </div>
        </div>
    </div>
</div>