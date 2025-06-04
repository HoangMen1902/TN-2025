<div class="container m-auto flex w-[1200px] gap-[5px]">
    <div class="flex rounded-l h-348 mb-6 bg-light">
        <div class="wrapper ">
            <div class="">
                <div class="w-full space-y-6 p-4">

                    <!-- DANH MỤC -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">DANH MỤC CHÍNH</h3>

                        @foreach ($categories as $category)
                            @if ($category->children->isNotEmpty())
                                <div x-data="{ open: false, openMore: false }" class="category-block">
                                    <button @click="open = !open" type="button"
                                        class="flex items-center justify-between w-full text-gray-500 hover:text-black cursor-pointer">
                                        <span>
                                            {{ $category->name }}
                                            <span class="ml-1 text-gray-400">({{ $category->products->count() }})</span>
                                        </span>

                                        <svg x-show="!open" class="w-4 h-4 transform transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <svg x-show="open" x-cloak class="w-4 h-4 transform transition-transform rotate-180"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Danh mục con có checkbox --}}
                                    <div x-show="open" x-transition x-cloak class="pl-6 mt-2 space-y-2 text-sm text-gray-700">
                                        @php
                                            $children = $category->children;
                                            $limit = 7;
                                        @endphp

                                        @foreach ($children->take($limit) as $child)
                                            <label
                                                class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                                <input type="checkbox" wire:model.live="selectedCategoryIds"
                                                    value="{{ $child->id }}"
                                                    class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                                <span class="text-gray-500">{{ $child->name }}</span>
                                                <span class="ml-1 text-gray-400">({{ $child->products->count() ?? 0 }})</span>
                                            </label>
                                        @endforeach

                                        {{-- Ẩn các mục vượt quá limit, chỉ hiện khi openMore = true --}}
                                        <template x-if="openMore">
                                            <div>
                                                @foreach ($children->slice($limit) as $child)
                                                    <label
                                                        class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                                        <input type="checkbox" wire:model.live="selectedCategoryIds"
                                                            value="{{ $child->id }}"
                                                            class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                                        <span class="text-gray-500">{{ $child->name }}</span>
                                                        <span
                                                            class="ml-1 text-gray-400">({{ $child->products->count() ?? 0 }})</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </template>

                                        {{-- Nút xem thêm / thu gọn --}}
                                        @if ($children->count() > $limit)
                                            <button type="button" @click="openMore = !openMore"
                                                class="text-blue-600 text-sm mt-1 hover:underline">
                                                <span x-text="openMore ? 'Thu gọn' : 'Xem thêm'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Danh mục không có con vẫn giữ checkbox bình thường --}}

                                @php
                                    $limit = 7;
                                @endphp

                                @if ($loop->index < $limit)
                                    <label class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                        <input type="checkbox" wire:model.live="selectedCategoryIds" value="{{ $category->id }}"
                                            class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                        <span class="text-gray-500">{{ $category->name }}</span>
                                        <span class="ml-1 text-gray-400">({{ $category->products->count() ?? 0 }})</span>
                                    </label>
                                @elseif ($loop->index == $limit)
                                    {{-- Ở đây cần hiện nút xem thêm cho danh mục chính --}}
                                    <div x-data="{ openMore: false }">
                                        <template x-if="openMore">
                                            <label
                                                class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                                <input type="checkbox" wire:model.live="selectedCategoryIds"
                                                    value="{{ $category->id }}"
                                                    class="accent-blue-500 w-4 h-4 border border-blue-500 text-gray-400">
                                                <span class="text-gray-500">{{ $category->name }}</span>
                                                <span class="ml-1 text-gray-400">({{ $category->products->count() ?? 0 }})</span>
                                            </label>
                                        </template>

                                        <button type="button" @click="openMore = !openMore"
                                            class="text-blue-600 text-sm mt-1 hover:underline">
                                            <span x-text="openMore ? 'Thu gọn' : 'Xem thêm'"></span>
                                        </button>
                                    </div>
                                @endif
                            @endif
                        @endforeach
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
                    <div class="space-y-3 border-b pb-4" x-data="{ openMorePublisher: false }">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">Nhà xuất bản</h3>

                        <div class="space-y-2 text-sm text-gray-700">
                            @php
                                $limit = 7;
                            @endphp

                            @foreach ($publishers->take($limit) as $data)
                                <label class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                    <input type="checkbox"
                                        class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400"
                                        value="{{$data->id}}" wire:model.live="selectedPublisherIds">
                                    <span class="text-gray-500">{{ $data->publisher_name }}</span><span
                                        class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() ?? 0 }})</span>
                                </label>
                            @endforeach

                            <template x-if="openMorePublisher">
                                <div>
                                    @foreach ($publishers->slice($limit) as $data)
                                        <label
                                            class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                            <input type="checkbox"
                                                class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400"
                                                value="{{$data->id}}" wire:model.live="selectedPublisherIds">
                                            <span class="text-gray-500">{{ $data->publisher_name }}</span><span
                                                class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() ?? 0 }})</span>
                                        </label>
                                    @endforeach
                                </div>
                            </template>

                            @if ($publishers->count() > $limit)
                                <button type="button" @click="openMorePublisher = !openMorePublisher"
                                    class="text-blue-600 text-sm mt-1 hover:underline">
                                    <span x-text="openMorePublisher ? 'Thu gọn' : 'Xem thêm'"></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 mt-4" x-data="{ openMoreTag: false }">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">Thẻ sản phẩm</h3>

                        <div class="space-y-2 text-sm text-gray-700">

                            @php
                                $limit = 7;
                            @endphp

                            @foreach ($tags->take($limit) as $data)
                                <label class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                    <input type="checkbox"
                                        class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400"
                                        value="{{$data->id}}" wire:model.live="selectedTagIds">
                                    <span class="text-gray-500">{{ $data->tag_name }}</span><span
                                        class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() }})</span>
                                </label>
                            @endforeach

                            <template x-if="openMoreTag">
                                <div>
                                    @foreach ($tags->slice($limit) as $data)
                                        <label
                                            class="flex items-center gap-2 hover:text-black transition-colors cursor-pointer">
                                            <input type="checkbox"
                                                class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400"
                                                value="{{$data->id}}" wire:model.live="selectedTagIds">
                                            <span class="text-gray-500">{{ $data->tag_name }}</span><span
                                                class="ml-1 text-gray-400">({{ $data->products->where('product_status', 'active')->count() }})</span>
                                        </label>
                                    @endforeach
                                </div>
                            </template>

                            @if ($tags->count() > $limit)
                                <button type="button" @click="openMoreTag = !openMoreTag"
                                    class="text-blue-600 text-sm mt-1 hover:underline">
                                    <span x-text="openMoreTag ? 'Thu gọn' : 'Xem thêm'"></span>
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
                    <option value="gia-giam-desc">Giá Bán thấp đến cao</option>
                    <option value="gia-giam-asc">Giá Bán cao đến thấp</option>
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
                @if ($search)
                    <h4>Kết quả tìm kiếm cho: <strong>"{{ $search }}"</strong></h4>
                @endif
                <div class=" grid grid-cols-3 gap-3 gap-[10px]">

                    @foreach ($products as $product)
                        <div
                            class="w-full overflow-hidden relative flex items-center justify-center h-[390px] rounded-[10px] aspect-[16/9] mt-4 border border-gray-300 group transition-all duration-500 ease-in-out transform hover:scale-[1.02] hover:shadow-lg">


                            <div
                                class="absolute bottom-12 right-4 z-10 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 ease-in-out">
                                <form action="/add-to-cart" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <button
                                        class="bb-primary text-white px-4 py-2 rounded-3xl shadow-md border border-transparent transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-600 hover:border-blue-600 hover:shadow-lg hover:scale-105 hover:bg-white">
                                        Mua ngay
                                    </button>
                                </form>
                            </div>
 
                            <div style="cursor:pointer" onclick="window.location.href='/chi-tiet/{{ $product->id }}';"
                                class="product__slide__image border-b border-gray-300 overflow-hidden">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                    class="embla__slide__background block w-full h-full transition-transform duration-500 ease-in-out group-hover:scale-105">
                            </div>


                            <div class="product__slide__number flex flex-wrap content-around p-2">
                                <div>
                                    <p class="text-base line-clamp-2">{{ $product->name }}</p>
                                </div>

                                <div class="flex items-center space-x-1 w-[100%]">
                                    @for ($j = 1; $j <= 5; $j++)
                                        <svg class="w-4 h-4 {{ $j <= ($product->rating ?? 4) ? 'text-yellow-300' : 'text-gray-200' }}"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                            <path
                                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                        </svg>
                                    @endfor
                                    <span class="text-xs">{{ $product->reviews_count ?? 20 }}</span>
                                </div>
                                <div>

                                </div>
                                @foreach ($product->categories as  $category)
                                {{ $category->name ?? 'Không xác định' }}
                                @endforeach


                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price">
                                        {{ number_format($product->productSkus->first()->sale_price ?? $product->productSkus->first()->price ?? 0) }}đ
                                    </p>
                                    @if (!is_null($product->productSkus->first()->sale_price))
                                        <span class="product__slide__number__imgs__price-sale ml-1 opacity-50 line-through">
                                            {{ number_format($product->productSkus->first()->price) }}đ
                                        </span>
                                    @endif
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
                            <a wire:click.prevent="previousPage" href="#" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700
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
                            <a wire:click.prevent="nextPage" href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700
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


    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-sub').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const subcategories = this.closest('div').querySelector('.subcategories');
                if (subcategories) {
                    subcategories.classList.toggle('hidden', !this.checked);
                }
            });


            if (checkbox.checked) {
                const subcategories = checkbox.closest('div').querySelector('.subcategories');
                if (subcategories) {
                    subcategories.classList.remove('hidden');
                }
            }
        });
    });


    const selected = new Set();
    const buttons = document.querySelectorAll('.category-btn');
    const hiddenInput = document.getElementById('selected-categories');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const value = button.dataset.value;
            if (selected.has(value)) {
                selected.delete(value);
                button.classList.remove('bg-blue-600');
                button.classList.add('bg-gray-700');
            } else {
                selected.add(value);
                button.classList.remove('bg-gray-700');
                button.classList.add('bg-blue-600');
            }

            hiddenInput.value = Array.from(selected).join(',');
        });
    });



</script>