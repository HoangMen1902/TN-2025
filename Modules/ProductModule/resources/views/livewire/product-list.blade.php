<div class="container m-auto flex w-[1200px] gap-[5px]">
    <div class="flex rounded-l  bg-light">
        <div class="wrapper">
            <div class="">
                <div class="w-72 p-4  rounded-l space-y-4 border border-gray-300 ">
                    <h2 class="text-lg font-semibold"></h2>

                    <!-- Price Range -->
                    <div>
                        <label class="block border-gray-300  mb-1 font-bold">Khoản giá</label>
                        <input type="range" class="w-full accent-blue-500" min="0" max="5000" />
                        <div class="flex justify-between mt-2 gap-2">
                            <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="300">
                            <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="3500">
                        </div>
                    </div>

                    <!-- Sales -->
                    <div>
                        <label class="block border-gray-300  mb-1 font-bold">Giảm giá</label>
                        <input type="range" class="w-full accent-blue-500" min="0" max="100" />
                        <div class="flex justify-between mt-2 gap-2">
                            <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="1">
                            <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="100">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block mb-1 font-bold">Loại sản phẩm</label>
                        <div id="category-buttons" class="grid grid-cols-2 gap-2">
                            <button type="button" data-value="Gaming"
                                class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Văn
                                Học</button>
                            <button type="button" data-value="Electronics"
                                class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Truyện</button>
                            <button type="button" data-value="Phone"
                                class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Tiếng
                                Anh</button>
                            <button type="button" data-value="TV/Monitor"
                                class="category-btn px-2 py-1 rounded border border-gray-300  hover:bg-blue-500">Thiếu
                                Nhi</button>
                            <button type="button" data-value="Laptop"
                                class="category-btn px-2 py-1 rounded border border-gray-300  hover:bg-blue-500">Trinh
                                Thám</button>
                            <button type="button" data-value="Watch" class="category-btn px-2 py-1 rounded border border-gray-300  h
                   over:bg-blue-500">Sách Giải</button>
                        </div>
                    </div>

                    <!-- Hidden input to send to backend -->
                    <input type="hidden" id="selected-categories" name="categories" value="">

                    <!-- State -->
                    {{-- <div>
                        <label class="block border-gray-300  mb-1">State</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="state" checked class="accent-blue-500">
                                <span>All</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="state" class="accent-blue-500">
                                <span>New</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="state" class="accent-blue-500">
                                <span>Refurbished</span>
                            </label>
                        </div>
                    </div>
                    --}}
                    <!-- Buttons -->
                    <div class="flex gap-2">
                        {{-- <button class="flex-1 bg-blue-600 hover:bg-blue-700  px-4 py-2 rounded">Thêm kết quả
                            khác</button> --}}
                        <button class="bg-blue-600 text-white hover:bg-blue-700  px-4 py-2 rounded">Đặt lại</button>
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
</script>