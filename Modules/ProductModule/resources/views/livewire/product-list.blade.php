<div class="container m-auto flex w-[1200px] gap-[5px]">
    <div class="flex rounded-l  bg-light">
        <div class="wrapper">
            <div class="">
                <div class="w-full space-y-6 p-4">

                    <!-- DANH MỤC -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">DANH MỤC CHÍNH</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.2px] text-gray-400">
                                <span class="text-gray-500">Sách Tiếng Việt</span><span class="ml-1 text-gray-400">(62)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> Foreign Books</span><span class="ml-1 text-gray-400">(20)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> Văn Phòng Phẩm - Dụng Cụ Học Sinh</span><span class="ml-1 text-gray-400">(6)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> Đồ Chơi</span><span class="ml-1 text-gray-400">(3)</span>
                            </label>
                        </div>
                    </div>

                    <!-- GIÁ -->
                    <div class="space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">GIÁ</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                             <span class="text-gray-500">   0đ - 150,000đ</span><span class="ml-1 text-gray-400">(74)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> 150,000đ - 300,000đ</span><span class="ml-1 text-gray-400">(11)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> 300,000đ - 500,000đ</span><span class="ml-1 text-gray-400">(6)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> 500,000đ - 700,000đ</span><span class="ml-1 text-gray-400">(6)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> 700,000đ Trở Lên</span>
                            </label>
                        </div>
                        <div class="space-y-2 mt-4">
                            <p class="text-sm text-gray-700">Hoặc chọn mức giá phù hợp</p>

                            <div class="flex items-center gap-2">
                                <input type="number" id="priceInputMin" class="w-24 border rounded px-2 py-1 text-sm"
                                    placeholder="Từ">
                                <span>-</span>
                                <input type="number" id="priceInputMax" class="w-24 border rounded px-2 py-1 text-sm"
                                    placeholder="Đến">
                            </div>

                            <div id="price-slider"  class="mt-3 text-blue-600"></div>
                        </div>



                    </div>

                    <!-- THƯƠNG HIỆU -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800 uppercase text-sm">THƯƠNG HIỆU</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                                <span class="text-gray-500">Deli</span><span class="ml-1 text-gray-400">(3)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                               <span class="text-gray-500"> Paul Rubens</span><span class="ml-1 text-gray-400">(3)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="accent-blue-500 w-4 h-4 border border-blue-500 border-[0.5px] text-gray-400">
                              <span class="text-gray-500">  Chaang Chiia</span><span class="ml-1 text-gray-400">(1)</span>
                            </label>
                        </div>
                    </div>

                </div>


            </div>
        </div>

    </div>
    <div class="main-content rounded-r"
        style="border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;">
        <header class="sort-header">
            <div class="sort-header1">
                <div class="span">
                    <span>Sắp xếp theo:</span>
                </div>
                <select>
                    <option value="ban-chay-tuan">Bán Chạy Tuần</option>
                    <option value="ban-chay-thang">Bán Chạy Tháng</option>
                    <option value="ban-chay-nam">Bán Chạy Năm</option>
                    <option value="noi-bat-tuan">Nổi Bật Tuần</option>
                    <option value="noi-bat-thang">Nổi Bật Tháng</option>
                    <option value="noi-bat-nam">Nổi Bật Năm</option>
                    <option value="chiet-khau">Chiết khấu</option>
                    <option value="gia-ban">Giá Bán</option>
                    <option value="moi-nhat">Mới nhất</option>
                </select>
            </div>
            <div class="sort-header2">
                <select>
                    <option value="12-san-pham">12 sản phẩm</option>
                    <option value="24-san-pham">24 sản phẩm</option>
                    <option value="48-san-pham">48 sản phẩm</option>
                </select>
            </div>
        </header>

        <div class="">
            <div class="product__viewport">

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

                            <div style="cursor:pointer" onclick="window.location.href='/product-detail/{{ $product->id }}';"
                                class="product__slide__image border-b border-gray-300 overflow-hidden">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
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


                                <div>{{ $product->category->name ?? 'Không xác định' }}</div>


                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price">
                                        {{ number_format($product->productSkus->first()->price ?? 0) }}đ
                                    </p>
                                    @if ($product->productSkus->first()->original_price ?? false)
                                        <span class="product__slide__number__imgs__price-sale ml-1 opacity-50">
                                            {{ number_format($product->productSkus->first()->original_price) }}đ
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
                                        <a wire:click.prevent="gotoPage({{ $page }})" href="#" class="flex items-center justify-center px-4 h-10 leading-tight
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


    </div>
</div>


<script>
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


    var priceSlider = document.getElementById('price-slider');
    var priceInputMin = document.getElementById('priceInputMin');
    var priceInputMax = document.getElementById('priceInputMax');

    if (priceSlider) {
        noUiSlider.create(priceSlider, {
            start: [0, 10000000],
            connect: true,
            step: 10000,
            range: {
                'min': 0,
                'max': 10000000
            },
            format: {
                to: function (value) {
                    return Math.round(value);
                },
                from: function (value) {
                    return Number(value);
                }
            }
        });

        priceSlider.noUiSlider.on('update', function (values, handle) {
            const value = values[handle];
            if (handle === 0) {
                priceInputMin.value = value;
            } else {
                priceInputMax.value = value;
            }
        });
 
        priceInputMin.addEventListener('change', function () {
            priceSlider.noUiSlider.set([this.value, null]);
        });
        priceInputMax.addEventListener('change', function () {
            priceSlider.noUiSlider.set([null, this.value]);
        });
    }



</script>