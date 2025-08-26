<div class="container max-w-[1200px] mx-auto bg-white rounded mb-4">
    <!-- Grid Slider Header -->
    <div class="">
        <div class="bg-pink-100 mt-4 p-2 rounded flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/icon-menu/icon_dealhot_new.png"
                        class="mx-auto w-8 h-auto" alt="Hot Deal Icon">
                </div>
                <div class="ml-2 text-base font-bold text-gray-800 uppercase">Top 5 sản phẩm giống nhất</div>
            </div>
        </div>
    </div>

    <!-- Grid Slider Content -->
    <div>
        <div class="flex flex-wrap gap-3 p-3">
            @forelse ($products as $product)
                <a href="/chi-tiet/{{ $product->slug }}"
                    class="block w-full sm:w-[calc(50%-0.75rem)] md:w-[calc(20%-0.75rem)] mb-3 h-full">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            @if (!empty($product->thumbnail))
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" class="w-full h-40 object-contain"
                                    alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="w-full h-48 object-contain"
                                    alt="Placeholder">
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-2">
                                {{ $product->name }}
                            </h3>
                            <div class="flex items-center mt-0.5">
                                <p class="text-red-500 font-bold text-xs">
                                    {{ number_format(($product->productSkus->first()->sale_price ?? $product->productSkus->first()->price), 0, ',', '.') }}đ
                                </p>
                                @if ($product->productSkus->first()->price > $product->productSkus->first()->sale_price && isset($product->productSkus->first()?->sale_price))
                                    <span class="ml-1 bg-red-500 text-white text-[10px] font-bold px-1 py-0.5 rounded">
                                        Giảm
                                        {{ round(($product->productSkus->first()->price - $product->productSkus->first()->sale_price) / $product->productSkus->first()->price * 100) }}%
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-500 line-through text-[10px] {{isset($product->productSkus->first()->sale_price) ? '' : 'hidden'}}">
                                {{ number_format($product->productSkus->first()->price, 0, ',', '.') }}đ
                            </p>
                            <div class="relative w-full h-3 bg-gray-300 rounded-full overflow-hidden mt-1">
                                <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;">
                                </div>

                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-600 text-sm">Không tìm thấy sản phẩm nào.</p>
            @endforelse

        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = dropdown.previousElementSibling;
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</div>