<x-layouts.layout>
    <x-slot name="title">BeeBook - Giỏ hàng </x-slot>
    <div class="cart-container mx-auto my-5 w-4/5 bg-white p-5 shadow-md flex justify-between items-stretch">
        <div class="cart flex-1 flex flex-col bg-white p-5 border border-gray-300 mb-5 lg:mb-0 lg:mr-5">
            <form action="/delete-all-cart" method="post">
                <input type="hidden" name="method" value="POST">
                <button class="text-lg font-medium  hover:underline" name="delete-cart"
                    onclick="return confirm('Xóa toàn bộ sản phẩm')">Xóa toàn bộ sản phẩm</button>
            </form>
            <h3 class="text-center mt-7 text-2xl">Giỏ hàng</h3>
            <p class="text-center mt-3 text-lg  border-b-4 border-blue-600 w-72 mx-auto">Bạn được giao hàng miễn phí!
            </p>

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
                                <img class="w-24 h-auto" src="{{ $item->image }}" alt="{{ $item->sku }}">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">{{ $item->name }}</h2>
                                <p class="">{{ number_format($item->price, 0, ',', '.') }}đ</p>
                                <div class="list-none p-0 text-sm">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: {{ $item->description }}</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    @csrf
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text"
                                        name="quantity[{{ $item->id }}]" value="{{ $item->quantity }}"
                                        data-id="{{ $item->id }}">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</x-layouts.layout>