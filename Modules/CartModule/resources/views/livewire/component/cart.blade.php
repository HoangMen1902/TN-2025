<div class="">
    <button wire:click="deleteAll" class="text-lg font-medium hover:underline"
        onclick="return confirm('Xóa toàn bộ sản phẩm?')">
        Xóa toàn bộ sản phẩm
    </button>
    <h3 class="text-center mt-7 text-2xl">Giỏ hàng</h3>
    <p class="text-center mt-3 text-lg border-b-4 border-blue-600 w-72 mx-auto">Bạn được giao hàng miễn phí!
    </p>
    <div class="cart__content flex flex-col lg:flex-row justify-start px-5 mt-5">
        <div class="cart__products w-5/7">
            <div class="flex justify-between mb-7 border-b border-gray-400">
                <div class="w-1/7 font-bold">Hình ảnh</div>
                <div class="w-4/7 font-bold">Sản phẩm</div>
                <div class="w-1/7 font-bold">Số lượng</div>
                <div class="w-1/7 font-bold">Tổng</div>
            </div>
            @foreach ($cartItems as $item)
                <div class="w-full mb-5">
                    <div class="border-b p-2 flex border-gray-400">
                        <div class="w-1/7 p-2">
                            <img class="w-24 h-auto"
                                src="{{ $item->sku->product->thumbnail ?? 'https://via.placeholder.com/150' }}"
                                alt="{{ $item->sku->sku ?? 'SKU Image' }}">
                        </div>
                        <div class="w-4/7 p-2">
                            <h2 class="text-lg font-bold truncate max-w-100 mt-1">
                                {{ $item->sku->product->name ?? 'Tên sản phẩm' }} - {{ $item->sku->sku ?? 'SKU' }}
                            </h2>
                            <p>{{ number_format($item->sku->price ?? 0, 0, ',', '.') }}đ</p>
                            <div class="list-none p-0 text-sm ">
                                <div class="my-1 line-clamp-2">
                                    Mô tả sản phẩm:
                                    {{ \Illuminate\Support\Str::limit($item->sku->product->description ?? 'Không có mô tả', 150) }}
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
                        <div class="w-1/7 text-center mt-4">
                            {{ number_format(($item->sku->price ?? 0) * ($quantities[$item->id] ?? $item->quantity ?? 1), 0, ',', '.') }}
                            đ
                        </div>
                    </div>
                </div>
            @endforeach


            @if($cartItems->isEmpty())
                <p class="text-center text-gray-500">Giỏ hàng của bạn đang trống.</p>
            @endif


        </div>
        <div class="cart__summary w-2/7 ml-8 p-5 max-h-[230px] border border-gray-300 rounded-xl text-justify">
            <div class="cart__summary-item flex justify-between mb-3">
                <span class="font-medium">Tổng phụ</span>
                <span class="text-gray-700">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</span>
            </div>
            <div class="cart__summary-item flex justify-between mb-3">
                <h3 class="font-bold text-xl">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</h3>
            </div>
            <p class="text-sm text-gray-600">Phí ship sẽ được tính khi thanh toán</p>
            <div class="mt-5 flex justify-center">
                <a href="/thanh-toan"
                    class="bg-primary text-white font-bold py-2 px-4 rounded-xl transition duration-300 hover:bg-white hover:text-black">Thanh
                    toán</a>
            </div>
        </div>
    </div>



</div>