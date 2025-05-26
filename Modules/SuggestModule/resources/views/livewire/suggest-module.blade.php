
<div class="rounded-t-[20px] overflow-hidden my-6">
    <div class="bg-top bg-cover bg-no-repeat w-full"
        style="background-image: url('https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/banner_personalization.png');">
        <div class="mx-auto w-full max-w-[1400px] px-4 pt-30 pb-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach ($displayedProducts->take(5) as $product)
                    <a href="/chi-tiet/{{ $product->slug }}" class="block">
                        <div
                            class="bg-white p-4 rounded-[10px] shadow-sm hover:shadow-lg transition duration-300 flex flex-col h-full">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="ảnh sản phẩm"
                                class="w-full aspect-[3/4] object-contain mx-auto">
                            <h3 class="text-sm mt-2 font-medium leading-5 line-clamp-2">{{ $product->name }}</h3>
                            <div class="text-red-600 font-bold text-lg">
                                {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                @if ($product->discount > 0)
                                    <span class="bg-red-500 text-white text-xs font-semibold px-1 py-0.5 rounded ml-1">
                                        -{{ $product->discount }}%
                                    </span>
                                @endif
                            </div>
                            @if ($product->price > $product->sale_price)
                                <div class="text-gray-400 text-sm line-through">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </div>
                            @endif
                            <div class="relative w-full h-4 bg-gray-300 rounded-full overflow-hidden mt-2">
                                <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;"></div>
                                <div class="absolute w-full text-center text-white text-sm leading-4">Đã bán 6</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="rounded-b-[20px] pb-6 px-4">
        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($displayedProducts as $product)
                <a href="/chi-tiet/{{ $product->id }}" class="block">
                    <div
                        class="bg-white p-4 rounded-[10px] shadow-sm hover:shadow-lg transition duration-300 flex flex-col h-full">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="ảnh sản phẩm"
                            class="w-full aspect-[3/4] object-contain mx-auto">
                        <h3 class="text-sm mt-2 font-medium leading-5 line-clamp-2">{{ $product->name }}</h3>
                        <div class="text-red-600 font-bold text-lg">
                            {{ number_format($product->sale_price, 0, ',', '.') }} đ
                            @if ($product->discount > 0)
                                <span class="bg-red-500 text-white text-xs font-semibold px-1 py-0.5 rounded ml-1">
                                    -{{ $product->discount }}%
                                </span>
                            @endif
                        </div>
                        @if ($product->price > $product->sale_price)
                            <div class="text-gray-400 text-sm line-through">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </div>
                        @endif
                        <div class="relative w-full h-4 bg-gray-300 rounded-full overflow-hidden mt-2">
                            <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;"></div>
                            <div class="absolute w-full text-center text-white text-sm leading-4">Đã bán 6</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div> --}}

        <!-- Cute Button -->
        <div class="text-center mt-6">
            @if ($canLoadMore)
                <button wire:click="loadMore"
                    class="bg-pink-100 text-pink-600 border border-pink-300 font-semibold px-6 py-3 rounded-full shadow hover:bg-pink-200 transition duration-300 transform hover:scale-105">
                    🌸 <span class="mr-2">Xem thêm</span>
                    <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            @else
                <button wire:click="collapse"
                    class="bg-pink-100 text-pink-600 border border-pink-300 font-semibold px-6 py-3 rounded-full shadow hover:bg-pink-200 transition duration-300 transform hover:scale-105">
                    🌸 <span class="mr-2">Rút gọn</span>
                    <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Line Clamp Support -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
