<div class="container m-auto flex w-[1200px] gap-[5px]">
    <div class="flex rounded-l h-348 mb-6 bg-light">
        <div class="wrapper ">
            <div class="">
                <div class="w-full space-y-6 p-4">

                    <!-- DANH MỤC -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">NHÓM SẢN PHẨM</h3>
                        @if ($parentCategory)
                            <div class="font-bold text-base text-gray-700 mb-1">{{ $parentCategory->name }}</div>
                            <ul class="ml-2">
                                @foreach ($siblingCategories as $cat)
                                    <li>
                                        <a href="{{ route('store-category', ['categorySlug' => $cat->slug]) }}" class="block py-1 px-2 rounded
                                            {{ $cat->id == $category->id ? 'text-orange-600 font-semibold bg-orange-50' : 'text-gray-700 hover:bg-gray-100' }}
                                            ml-4">
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="font-bold text-base text-gray-700 mb-1">{{ $category->name }}</div>
                        @endif
                    </div>




                    <!-- GIÁ -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">GIÁ</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" wire:click="setPriceRange(0, 150000)"
                                    class="accent-blue-500 w-4 h-4">

                                <span class="text-gray-500"> 0đ - 150,000đ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" wire:click="setPriceRange(150000, 300000)"
                                    class="accent-blue-500 w-4 h-4">
                                <span class="text-gray-500"> 150,000đ - 300,000đ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" wire:click="setPriceRange(300000, 500000)"
                                    class="accent-blue-500 w-4 h-4">
                                <span class="text-gray-500"> 300,000đ - 500,000đ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" wire:click="setPriceRange(500000, 700000)"
                                    class="accent-blue-500 w-4 h-4">
                                <span class="text-gray-500"> 500,000đ - 700,000đ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" wire:click="setPriceRange(700000, 100000000)"
                                    class="accent-blue-500 w-4 h-4">
                                <span class="text-gray-500"> 700,000đ Trở lên</span>
                            </label>
                        </div>

                        <div class="space-y-3 mt-4">
                            <p class="text-sm text-gray-700">Chọn mức giá phù hợp</p>

                            <div class="flex items-center gap-2">
                                <input type="number" min="0" max="10000000" step="10000"
                                    class="w-24 border rounded px-2 py-1 text-sm" placeholder="Từ"
                                    wire:model.lazy="minPrice">
                                <span>-</span>
                                <input type="number" min="0" max="10000000" step="10000"
                                    class="w-24 border rounded px-2 py-1 text-sm" placeholder="Đến"
                                    wire:model.lazy="maxPrice">
                            </div>


                            <div class="flex flex-col gap-2">
                                <input type="range" min="0" max="10000000" step="10000" wire:model.lazy="minPrice"
                                    class="w-full accent-blue-600">
                                <input type="range" min="0" max="10000000" step="10000" wire:model.lazy="maxPrice"
                                    class="w-full accent-blue-600">
                                <div class="text-sm text-gray-500">
                                    Giá từ: {{ number_format($minPrice) }}đ - {{ number_format($maxPrice) }}đ
                                </div>
                            </div>
                        </div>




                    </div>

                    <!-- THƯƠNG HIỆU -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">Nhà xuất bản</h3>

                        <div class="space-y-2 text-sm text-gray-700">
                            @php $limit = 7; @endphp
                            @foreach ($publishers->take($showMorePublishers ? $publishers->count() : $limit) as $data)
                                <label class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedPublisherIds" value="{{ $data->id }}"
                                        wire:key="publisher-{{ $data->id }}"
                                        class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                    <span class="text-gray-500">{{ $data->publisher_name }}</span>
                                    <span
                                        class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() ?? 0 }})</span>
                                </label>
                            @endforeach
                            @if ($publishers->count() > $limit)
                                <button type="button" wire:click="toggleShowMore('publisher')"
                                    class="text-blue-600 text-sm mt-1 hover:underline">
                                    {{ $showMorePublishers ? 'Thu gọn' : 'Xem thêm' }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 mt-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">Thẻ sản phẩm</h3>

                        <div class="space-y-2 text-sm text-gray-700">

                            @foreach ($tags->take($showMoreTags ? $tags->count() : $limit) as $data)
                                <label class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedTagIds" value="{{ $data->id }}"
                                        wire:key="tag-{{ $data->id }}"
                                        class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                    <span class="text-gray-500">{{ $data->tag_name }}</span>
                                    <span
                                        class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() }})</span>
                                </label>
                            @endforeach
                            @if ($tags->count() > $limit)
                                <button type="button" wire:click="toggleShowMore('tag')"
                                    class="text-blue-600 text-sm mt-1 hover:underline">
                                    {{ $showMoreTags ? 'Thu gọn' : 'Xem thêm' }}
                                </button>
                            @endif
                        </div>
                    </div>


                </div>


            </div>
        </div>

    </div>
    <div class="main-content min-h-[500px] rounded-r"
        style="border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;">
        <header class="sort-header">
            <div class="sort-header1">
                <div class="span">
                    <span>Sắp xếp theo:</span>
                </div>
                <select wire:model.live="sortOrder">
                    <option value="desc">Mới nhất</option>
                    <option value="asc">Cũ nhất</option>
                    <option value="ban-chay-thang">Bán Chạy Tháng</option>
                    <option value="chiet-khau">Giảm giá</option>
                    <option value="gia-giam-asc">Giá Bán thấp đến cao</option>
                    <option value="gia-giam-desc">Giá Bán cao đến thấp</option>
                </select>
            </div>
            <div class="sort-header2">
                <select wire:model.live="perPage">
                    <option value="12">12 sản phẩm</option>
                    <option value="24">24 sản phẩm</option>
                    <option value="48">48 sản phẩm</option>
                </select>
            </div>
        </header>

        <div class="">
            <div class="product__viewport">

                <div class=" grid grid-cols-3 gap-3 gap-[10px]">

                    @foreach ($products as $product)
                        <div
                            class="product-card w-70 bg-white rounded-[10px] shadow-md overflow-hidden m-2 flex flex-col hover:scale-[1.02] hover:shadow-lg transition-all duration-300">
                            <div class="w-full h-[220px] bg-gray-100 flex items-center justify-center overflow-hidden cursor-pointer"
                                onclick="window.location.href='/chi-tiet/{{ $product->slug }}';">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                    class="object-contain max-h-full max-w-full transition-transform duration-500 ease-in-out hover:scale-105">
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <div class="font-medium text-[15px] min-h-[40px] mb-2 leading-tight line-clamp-2">
                                    {{ $product->name }}
                                </div>
                                <div class="flex items-center mb-2">
                                    <span class="text-red-600 font-semibold text-[18px]">
                                        {{ number_format($product->productSkus->first()->sale_price ?? $product->productSkus->first()->price ?? 0) }}
                                        đ
                                    </span>
                                    @php
                                        $price = $product->productSkus->first()->price ?? 0;
                                        $sale = $product->productSkus->first()->sale_price ?? $price;
                                        $discount = $price > $sale && $price > 0 ? round((($price - $sale) / $price) * 100) : 0;
                                    @endphp
                                    @if ($discount > 0)
                                        <span class="bg-red-600 text-white text-[13px] font-medium rounded px-2 py-0.5 ml-2">
                                            -{{ $discount }}%
                                        </span>
                                    @endif

                                    @if (!is_null($product->productSkus->first()->sale_price))

                                        <span class="text-gray-400 text-[14px] line-through ml-2">
                                            {{ number_format($product->productSkus->first()->price) }} đ
                                        </span>

                                    @endif
                                </div>

                                <div class="flex items-center gap-1 mb-2">
                                    @for ($j = 1; $j <= 5; $j++)
                                        <svg class="w-4 h-4 {{ $j <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-200' }}"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                            <path
                                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                        </svg>
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count ?? 20 }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
        @php
            $start = max(1, $products->currentPage() - 2);
            $end = min($products->lastPage(), $products->currentPage() + 2);
        @endphp

        @for ($page = $start; $page <= $end; $page++)

        @endfor

        @if ($products->lastPage() > 1)
            <div class="mt-6 flex justify-evenly">
                <nav aria-label="Page navigation example">
                    <ul class="inline-flex -space-x-px text-base h-10">

                        <li>
                            <a wire:click.prevent="previousPage" href="#"
                                class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700
                                                                       {{ $products->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                                Trở về
                            </a>
                        </li>

                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    <li>
                                        <a wire:click.prevent="gotoPage({{ $page }})" href="#"
                                            class="flex items-center justify-center px-4 h-10 leading-tight
                                                                                                                                                                                                                           {{ $products->currentPage() === $page
                            ? 'text-white border border-gray-300 bg-blue-700 hover:bg-blue-100'
                            : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700' }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                        @endforeach

                        <li>
                            <a wire:click.prevent="nextPage" href="#"
                                class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700
                                                                       {{ !$products->hasMorePages() ? 'pointer-events-none opacity-50' : '' }}">
                                Tiếp
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif



    </div>
</div>


<script>






</script>