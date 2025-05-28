<div class="">
    <form class="hidden" method="POST" action="/thanh-toan" id="addItemCheckout">
        @csrf
    </form>
    <button wire:click="deleteAll" class="text-lg font-medium hover:underline cursor-pointer">
        Xóa toàn bộ sản phẩm
    </button>
    <h3 class="text-center mt-7 text-2xl">Giỏ hàng</h3>
    <p class="text-center mt-3 text-lg border-b-4 border-blue-600 w-72 mx-auto">
    </p>
    <div class="cart__content flex flex-col justify-start px-5 mt-5">
        <div class="cart__products">
            <div class="flex justify-between mb-7 border-b border-gray-400">
                <div class="w-1/7 font-bold">Chọn</div>
                <div class="w-1/7 font-bold">Hình ảnh</div>
                <div class="w-4/7 font-bold">Sản phẩm</div>
                <div class="w-1/7 font-bold">Số lượng</div>
                <div class="w-1/7 font-bold text-center">Tổng</div>
            </div>
            @foreach ($cartItems as $item)
                <div class="w-full mb-5">
                    <div class="border-b p-2 flex border-gray-400">
                        <div class="inline-flex items-center w-1/7 p-2">
                            <label class="relative flex items-center cursor-pointer"
                                for="blue-600-{{$item->sku->id ?? $item->combo->id}}">
                                <input type="checkbox"
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-blue-400 transition-all"
                                    id="blue-600-{{$item->sku->id ?? $item->combo->id}}" name="cart_id[]"
                                    value="{{$item->id}}" form="addItemCheckout" />
                                <span
                                    class="absolute bg-blue-600 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                            </label>
                        </div>
                        <div class="w-1/7 p-2">
                            @php
                                $image = $item->sku->images[0] ?? $item->combo->images[0];
                            @endphp
                            <img class="w-24 h-auto"
                                src="{{ asset('storage/' . $image) }}"
                                alt="{{ $item->sku->sku ?? 'SKU Image' }}" style="object-cover w-full h-full">
                        </div>
                        <div class="w-4/7 p-2">
                            <h2 class="text-base font-bold truncate max-w-100 mt-1">
                                {{ $item->sku->product->name ?? $item->combo->combo_name ?? 'Tên sản phẩm' }} - {{ $item->sku->sku ?? 'Combo' }}
                            </h2>
                            <p class="text-red-600 font-bold">{{ number_format($item->sku->sale_price ?? $item->combo->sale_price ?? 0, 0, ',', '.') }}đ</p>
                            <div class="list-none p-0 text-sm ">
                                <div class="my-1 line-clamp-2">
                                    {!! $item->sku->product->short_description ?? $item->combo->description ?? 'Không có mô tả' !!}
                                </div>
                            </div>
                        </div>
                        <div class="w-1/7 p-2 flex flex-col items-center justify-center">
                            <div class="flex items-center ">
                                <button
                                    class="w-4 h-4 rounded-full border border-gray-500 text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                    wire:click="decreaseQuantity({{ $item->id }})">−</button>

                                <span class="w-10 text-center">{{ $quantities[$item->id] ?? 1 }}</span>

                                <button
                                    class="w-4 h-4 rounded-full border border-gray-500 text-xl leading-none flex items-center justify-center hover:bg-gray-200"
                                    wire:click="increaseQuantity({{ $item->id }})">+</button>
                            </div>

                            <button wire:click="removeItem({{ $item->id }})" class="mt-2 text-gray-600 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="w-1/7 text-center text-red-600 flex justify-center items-center ">
                            {{ number_format(($item->sku->sale_price ?? $item->combo->sale_price ?? 0) * ($quantities[$item->id] ?? $item->quantity ?? 1), 0, ',', '.') }}
                            VNĐ
                        </div>
                    </div>
                </div>
            @endforeach


            @if($cartItems->isEmpty())
                <p class="text-center text-gray-500">Giỏ hàng của bạn đang trống.</p>
            @endif


        </div>
        <div class="cart__summary w-2/7 ml-8 p-5 max-h-[230px] border border-gray-300 rounded-xl text-justify self-end">
            <div class="cart__summary-item flex justify-between mb-3">
                <span class="font-medium">Tổng phụ</span>
                <span class="text-red-700">{{ number_format($subtotal ?? 0, 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="cart__summary-item flex justify-between mb-3">
                <h3 class="font-bold text-xl text-red-700">{{ number_format($subtotal ?? 0, 0, ',', '.') }} VNĐ</h3>
            </div>
            <p class="text-sm text-gray-600">Phí ship sẽ được tính khi thanh toán</p>
            <div class="mt-5 flex justify-center">
                <button
                    class="bg-primary text-white font-bold py-2 px-4 rounded-xl transition duration-300 hover:bg-white cursor-pointer"
                    form="addItemCheckout">Thanh
                    toán</button>
            </div>
        </div>
    </div>



</div>