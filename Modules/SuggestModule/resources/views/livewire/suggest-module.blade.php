<div class="suggest-module-container">
    <div class="h-auto my-3 bg-gradient-to-b from-green-400 via-green-200 to-white rounded-xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <img class="h-20" src="{{ asset('assets/images/bongden.png') }}" alt="BeeBook Logo">

                <div class="text-white">

                    <h2 class="text-3xl font-bold">Gợi ý cho bạn</h2>
                    <p class="text-pink-100 text-sm mt-1">Những sản phẩm BeeBook gợi ý cho bạn <span
                            class="ml-1">😊</span></p>
                </div>
            </div>
        </div>

        <div class="products-grid grid grid-cols-5 gap-4">
            @foreach ($displayedProducts as $product)
                <a href="/chi-tiet/{{ $product->id }}" class="block">
                    <div
                        class="product-card bg-white p-4 rounded shadow-sm hover:shadow-lg transition-shadow duration-300 rounded-[10px]">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-contain mx-auto">
                        <h3 class="text-sm mt-2 font-medium leading-5 line-clamp-2">{{ $product->name }}</h3>
                        <div class="text-red-600 font-bold text-lg">
                            {{ number_format($product->sale_price, 0, ',', '.') }} đ
                            @if ($product->discount > 0)
                                <span
                                    class="bg-red-500 text-white text-xs font-semibold px-1 py-0.5 rounded ml-1">-{{ $product->discount }}%</span>
                            @endif
                        </div>
                        @if ($product->price > $product->sale_price)
                            <div class="text-gray-400 text-sm line-through">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </div>
                        @endif
                        <div class="relative w-full h-4 bg-gray-300 rounded-full overflow-hidden mt-2">
                            <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;">
                            </div>
                            <div class="absolute w-full text-center text-white text-sm leading-4">Đã bán 6</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-6">
            @if ($canLoadMore)
                <button wire:click="loadMore"
                    class="bg-white text-green-600 font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <span class="mr-2">Xem thêm sản phẩm</span>
                    <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            @else
                <button wire:click="collapse"
                    class="bg-white text-green-600 font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <span class="mr-2">Rút gọn</span>
                    <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 1280px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .suggest-module-container .h-auto {
                padding: 1rem;
            }
        }

        @media (max-width: 640px) {
            .products-grid {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
</div>
