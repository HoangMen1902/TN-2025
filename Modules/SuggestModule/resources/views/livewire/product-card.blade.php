<a href="/chi-tiet/{{ $product->slug }}" class="block cursor-pointer">
    <div class="product-card bg-white p-3 sm:p-4 rounded-[10px] shadow-sm hover:shadow-lg transition duration-300 flex flex-col">
        <div class="w-full aspect-[3/4] flex items-center justify-center">
            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                class="w-full h-full object-contain max-h-[180px] sm:max-h-[230px] md:max-h-[250px]">
        </div>
        <h3 class="text-xs sm:text-sm font-medium leading-5 line-clamp-2 mt-2 min-h-[2rem] sm:min-h-[2.5rem]">
            {{ $product->name }}
        </h3>
        <div class="mt-auto flex flex-col gap-1">
            <div class="text-red-600 font-bold text-sm sm:text-base md:text-lg flex items-center flex-wrap gap-1">
                {{ number_format($product->sale_price, 0, ',', '.') }} đ
                @if ($product->discount > 0)
                    <span class="bg-red-500 text-white text-[10px] sm:text-xs font-semibold px-1 py-0.5 rounded">
                        -{{ $product->discount }}%
                    </span>
                @endif
            </div>

            @if ($product->price > $product->sale_price)
                <div class="text-gray-400 text-xs sm:text-sm line-through">
                    {{ number_format($product->price, 0, ',', '.') }} đ
                </div>
            @endif
            <div class="relative w-full h-3 sm:h-4 bg-gray-300 rounded-full overflow-hidden mt-1">
                <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full"
                    style="width: {{ $product->percent_sold }}%;"></div>
                <div class="absolute w-full text-center text-white text-[10px] sm:text-xs leading-3 sm:leading-4">
                    {{ $product->percent_sold }}% đã bán
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 639px) {
            .product-card {
                height: 360px !important;
            }
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .product-card {
                height: 460px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .product-card {
                height: 420px !important;
            }
        }

        @media (min-width: 1024px) {
            .product-card {
                height: 410px !important;
            }
        }

        @media (min-width: 1280px) {
            .product-card {
                height: 400px !important;
            }
        }
    </style>
</a>
