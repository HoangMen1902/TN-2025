<x-layouts.layout>

    <div class="container max-w-[1200px] mx-auto">
        <div class="flex h-[330px] my-3  ">
            <!-- Div bên trái -->
            <div class="w-4/6 p-1 h-full">
                <div id="default-carousel" class="relative w-[103%] h-full max-w-3xl" data-carousel="slide">
                    <div id="indicators-carousel" class="relative w-[103%] h-full" data-carousel="static">
                        <!-- Carousel wrapper -->
                        <div class="relative h-full overflow-hidden rounded-lg">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/muasamkhongtienmat_840x320T525.png"
                                    class="absolute block w-[103%] h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="Banner 1">
                            </div>
                            <!-- Item 2 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/CTT5_Resize1505_840x320.png"
                                    class="absolute block w-[103%] h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="Banner 2">
                            </div>
                            <!-- Item 3 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/muasamkhongtienmat_840x320T525.png"
                                    class="absolute block w-[103%] h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="...">
                            </div>
                            <!-- Item 4 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/CTT5_Resize1505_840x320.png"
                                    class="absolute block w-[103%] h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="...">
                            </div>
                            <!-- Item 5 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/muasamkhongtienmat_840x320T525.png"
                                    class="absolute block w-[103%] h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="...">
                            </div>

                        </div>
                        <div
                            class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                                data-carousel-slide-to="0"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                                data-carousel-slide-to="1"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                                data-carousel-slide-to="2"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4"
                                data-carousel-slide-to="3"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5"
                                data-carousel-slide-to="4"></button>
                        </div>
                        <!-- Slider controls -->
                        <button type="button"
                            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-prev>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 1 1 5l4 4" />
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button"
                            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-next>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>


            <div class="w-2/6 h-full flex flex-col">
                <div class="w-full h-1/2 p-1">
                    <img class="w-full h-full object-cover rounded-lg shadow-lg"
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/homecreditT5_392x156_5.png"
                        alt="Image 1">
                </div>
                <div class="w-full h-1/2 p-1">
                    <img class="w-full h-full object-cover rounded-lg shadow-lg"
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/muasamkhongtienmatT5_392x156.png"
                        alt="Image 2">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-3 h-[215px] my-3">
            <div class="p-1">
                <a href="#">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Freeship_t5_310x210.png"
                        class="rounded block w-full h-full object-cover" alt="Banner 1">
                </a>
            </div>
            <div class="p-1">
                <a href="#">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/TrangQuaTang_T5_310x210_1.png"
                        class="rounded block w-full h-full object-cover" alt="Banner 2">
                </a>
            </div>
            <div class="p-1">
                <a href="#">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Mcbooks_small_310x210.png"
                        class="rounded block w-full h-full object-cover" alt="Banner 3">
                </a>
            </div>
            <div class="p-1">
                <a href="#">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/MayTinh_T5_310X210.png"
                        class="rounded block w-full h-full object-cover" alt="Banner 4">
                </a>
            </div>
        </div>

        <div class="grid grid-cols-10 gap-2 bg-light p-2 rounded">
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square">
                    <a href="">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Icon_1505_120x120_1.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">15.05</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/Thang-01-2025/IconFlashSale120x120.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Flash Sale</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Icon_DinhTi_120x120_1.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Đinh Tị</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="		https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Icon_MCbook_120x120.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">McBooks</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/icon-menu/Icon_MaGiamGia_8px_1.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Mã Giảm Giá</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="		https://cdn1.fahasa.com/media/wysiwyg/icon-menu/Icon_SanPhamMoi_8px_1.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Sản Phẩm Mới</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2024/Icon_GiamGia_120x120.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Sản Phẩm Được Trợ Giá</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-01-2024/ChoDoCu.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Phiên Chợ Đồ Cũ</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square"><a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/Duy-VHDT/ICON/Icon_DonSi_120x120.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Bán Sỉ</p>
                </div>
            </div>
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square">
                    <a href="">
                        <img src="	https://cdn1.fahasa.com/media/wysiwyg/Thang-06-2024/icon_ManngaT06.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">Manga</p>
                </div>
            </div>
        </div>
        <div class="h-[500] my-3 bg-light rounded  p-4">
            <div class="flex justify-between h-[50] ">
                <div class="1/2">
                    <div class="w-[150px]">
                        <img class=" block w-full h-full"
                            src="https://scontent.fsgn5-5.fna.fbcdn.net/v/t39.30808-6/495134330_122093663798876622_5731153365420282121_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=127cfc&_nc_ohc=F2y7w2NWNDYQ7kNvwH6d7_3&_nc_oc=AdlRtGSPaYJBpmsXho3k0SWs47erA2qRUBUnJeBJtqoY7fxzfv66EGwjPuYcU1vndrM&_nc_zt=23&_nc_ht=scontent.fsgn5-5.fna&_nc_gid=FBnoP0rtQIUuP1qDssVVsQ&oh=00_AfKnRvDJp7-JnQh4olQOYEKAzM0JyaqvEMKDtJD8tqcJ-Q&oe=68227425"
                            alt="">
                    </div>
                </div>
                <div class="flex items-center justify-center hover:text-sky-600 ">
                    <a href="" class="flex items-center">
                        <span class=" mr-1">Xem tất cả</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </a>
                </div>

            </div>

            <div class="product">
                <div class="product__viewport">
                    <div class="products__container">

                        {{-- <div class="product__slide">
                            <div style="cursor:pointer" onclick="window.location.href='/product-detail/1';"
                                class="product__slide__image">
                                <img src="/public/uploads/product_a.jpg" alt="Sản phẩm A" width="100px"
                                    class="embla__slide__background">
                            </div>
                            <div class="product__slide__number">
                                <div>
                                    <p class="product__slide__text clamp-text">Sản phẩm A</p>
                                </div>
                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price">100,000đ</p>
                                    <form action="/add-to-cart" method="post">
                                        <input type="hidden" name="method" value="POST">
                                        <input type="hidden" name="id" value="1">
                                        <button name="add-to-cart" style="cursor:pointer"
                                            class="product__slide__number__imgs__buy">Mua ngay</button>
                                    </form>
                                </div>
                            </div>
                        </div> --}}

                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="product__slide mt-4 relative border border-gray-300 group rounded-lg">
                            <div class="absolute bottom-40 right-4 z-10 
                                        opacity-0 translate-y-4 
                                        group-hover:opacity-100 group-hover:translate-y-0 
                                        transition-all duration-500 ease-in-out">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="sku_id" value="{{ $i }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded-3xl shadow-md
                   border border-transparent transition duration-300 ease-in-out
                   hover:bg-transparent hover:text-blue-600
                   hover:border-blue-600 hover:shadow-lg hover:scale-105">
                                        Mua ngay
                                    </button>
                                </form>

                            </div>

                            <div style="cursor:pointer" onclick="window.location.href='/product-detail/<?= $i ?>';"
                                class="product__slide__image border-b border-gray-300">
                                <img src="https://cdn1.fahasa.com/media/catalog/product/u/n/untitled-1-2_1.jpg"
                                    alt="Sản phẩm <?= $i ?>" width="100px"
                                    class="embla__slide__background  block w-full h-full ">
                            </div>
                            <div class="product__slide__number flex flex-wrap content-around ">
                                <div>
                                    <p class="text-base line-clamp-2"><?= $i ?> tung tung tung tung tung tung tung tung
                                        tung tung sahur tung tung tung</p>
                                </div>
                                <div class="flex items-center space-x-1 w-[100%]">
                                    <?php    for ($j = 1; $j <= 5; $j++): ?>
                                    <svg class="w-4 h-4 <?= $j <= 4 ? 'text-yellow-300' : 'text-gray-200' ?>"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                    <?php    endfor; ?>
                                    <span class="text-xs"> 20</span>
                                </div>
                                <div>
                                    category
                                </div>
                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price"><?= number_format($i * 100000000) ?>đ
                                    </p>
                                    <span
                                        class="product__slide__number__imgs__price-sale ml-1 opacity-50"><?= number_format($i * 100000000) ?>đ</span>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="product__controls">
                    <div class="product__progress">
                        <div class="product__progress__bar" style="transform:translate3d(0%,0px,0px)"></div>
                    </div>
                    <div class="product__buttons">
                        <button class="product__button product__button--prev" type="button" disabled="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                        </button>
                        <button class="product__button product__button--next" type="button" disabled="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-right">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>


        <div class=" bg-light p-2 my-3 rounded">
            <div class="flex justify-between h-[50] ">
                <div class="1/2">
                    <div>
                        <p class="ml-3 text-2xl font-bold">Doanh mục sản phẩm</p>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-10 gap-2   h-[170px] ">
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                    <a href="" class="h-[100px] ">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/HUYEN-1/3900000245517.png" class="rounded"
                            alt="Banner 1">
                    </a>
                    <div class="text-center mt-1">
                        <p class="text-sm">Bản Đồ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-3 bg-light rounded  p-4">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                    data-tabs-toggle="#default-tab-content" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab"
                            data-tabs-target="#profile" type="button" role="tab" aria-controls="profile"
                            aria-selected="false">Đẹp</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                            aria-controls="dashboard" aria-selected="false">Xấu quắc</button>
                    </li>
                    {{-- <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="settings-tab" data-tabs-target="#settings" type="button" role="tab"
                            aria-controls="settings" aria-selected="false">Settings</button>
                    </li>
                    <li role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab"
                            aria-controls="contacts" aria-selected="false">Contacts</button>
                    </li> --}}
                </ul>
            </div>
            <div id="default-tab-content">
                <div class="" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div
                            class="w-full overflow-hidden relative flex items-center justify-center h-[390px] rounded-[10px] aspect-[16/9] mt-4 border border-gray-300 group transition-all duration-500 ease-in-out transform hover:scale-[1.02] hover:shadow-lg">
                            <!-- Nút mua ngay -->
                            <div class="absolute bottom-12    right-4 z-10 
                                       opacity-0 translate-y-4 
                                       group-hover:opacity-100 group-hover:translate-y-0 
                                       transition-all duration-500 ease-in-out">
                                <form action="/add-to-cart" method="post">
                                    <input type="hidden" name="id" value="<?= $i ?>">
                                    <button class="bb-primary text-white px-4 py-2 rounded-3xl shadow-md
                                       border border-transparent
                                       transition duration-300 ease-in-out
                                       hover:bg-transparent hover:text-blue-600
                                       hover:border-blue-600 hover:shadow-lg
                                       hover:scale-105
                                       hover:bg-white
                                       ">
                                        Mua ngay
                                    </button>
                                </form>
                            </div>

                            <!-- Ảnh -->
                            <div style="cursor:pointer" onclick="window.location.href='/product-detail/<?= $i ?>';"
                                class="product__slide__image border-b border-gray-300 overflow-hidden">
                                <img src="https://cdn1.fahasa.com/media/catalog/product/u/n/untitled-1-2_1.jpg"
                                    alt="Sản phẩm <?= $i ?>" width="100px"
                                    class="embla__slide__background block w-full h-full transition-transform duration-500 ease-in-out group-hover:scale-105">
                            </div>

                            <!-- Thông tin -->
                            <div class="product__slide__number flex flex-wrap content-around p-2">
                                <div>
                                    <p class="text-base line-clamp-2"><?= $i ?> tung tung tung tung tung tung tung tung
                                        tung tung sahur tung</p>
                                </div>
                                <div class="flex items-center space-x-1 w-[100%]">
                                    <?php    for ($j = 1; $j <= 5; $j++): ?>
                                    <svg class="w-4 h-4 <?= $j <= 4 ? 'text-yellow-300' : 'text-gray-200' ?>"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                    <?php    endfor; ?>
                                    <span class="text-xs">20</span>
                                </div>
                                <div>category</div>
                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price"><?= number_format($i * 100000000) ?>đ
                                    </p>
                                    <span
                                        class="product__slide__number__imgs__price-sale ml-1 opacity-50"><?= number_format($i * 100000000) ?>đ</span>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div
                            class="w-full overflow-hidden relative flex items-center justify-center h-[390px] rounded-[10px] aspect-[16/9] mt-4 border border-gray-300 group transition-all duration-500 ease-in-out transform hover:scale-[1.02] hover:shadow-lg">
                            <div class="absolute bottom-12    right-4 z-10 
                                         opacity-0 translate-y-4 
                                         group-hover:opacity-100 group-hover:translate-y-0 
                                         transition-all duration-500 ease-in-out">
                                <form action="/add-to-cart" method="post">
                                    <input type="hidden" name="id" value="<?= $i ?>">
                                    <button class="bb-primary text-white px-4 py-2 rounded-3xl shadow-md
                                         border border-transparent
                                         transition duration-300 ease-in-out
                                         hover:bg-transparent hover:text-blue-600
                                         hover:border-blue-600 hover:shadow-lg
                                         hover:scale-105
                                         hover:bg-white
                                         ">
                                        Mua ngay
                                    </button>
                                </form>
                            </div>

                            <!-- Ảnh -->
                            <div style="cursor:pointer" onclick="window.location.href='/product-detail/<?= $i ?>';"
                                class="product__slide__image border-b border-gray-300 overflow-hidden">
                                <img src="	https://cdn1.fahasa.com/media/catalog/product/b/_/b_a-tr_c-manifest_1_2_1_2.jpg"
                                    alt="Sản phẩm <?= $i ?>" width="100px"
                                    class="embla__slide__background block w-full h-full transition-transform duration-500 ease-in-out group-hover:scale-105">
                            </div>

                            <!-- Thông tin -->
                            <div class="product__slide__number flex flex-wrap content-around p-2">
                                <div>
                                    <p class="text-base line-clamp-2"><?= $i ?> tung tung tung tung tung tung tung tung
                                        tung tung sahur tung</p>
                                </div>
                                <div class="flex items-center space-x-1 w-[100%]">
                                    <?php    for ($j = 1; $j <= 5; $j++): ?>
                                    <svg class="w-4 h-4 <?= $j <= 4 ? 'text-yellow-300' : 'text-gray-200' ?>"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                    <?php    endfor; ?>
                                    <span class="text-xs">20</span>
                                </div>
                                <div>category</div>
                                <div class="product__slide__number__imgs">
                                    <p class="product__slide__number__imgs__price"><?= number_format($i * 100000000) ?>đ
                                    </p>
                                    <span
                                        class="product__slide__number__imgs__price-sale ml-1 opacity-50"><?= number_format($i * 100000000) ?>đ</span>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel"
                    aria-labelledby="settings-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong
                            class="font-medium text-gray-800 dark:text-white">Settings tab's associated
                            content</strong>. Clicking another tab will toggle the visibility of this one for the next.
                        The tab JavaScript swaps classes to control the content visibility and styling.</p>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel"
                    aria-labelledby="contacts-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong
                            class="font-medium text-gray-800 dark:text-white">Contacts tab's associated
                            content</strong>. Clicking another tab will toggle the visibility of this one for the next.
                        The tab JavaScript swaps classes to control the content visibility and styling.</p>
                </div>
            </div>













        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emblaNode = document.querySelector('.product')
            const viewportNode = emblaNode.querySelector('.product__viewport')
            const prevBtn = emblaNode.querySelector('.product__button--prev')
            const nextBtn = emblaNode.querySelector('.product__button--next')
            const progressNode = emblaNode.querySelector('.product__progress__bar')

            const OPTIONS = { dragFree: true, containScroll: 'trimSnaps' }

            const emblaApi = EmblaCarousel(viewportNode, OPTIONS)

            const addTogglePrevNextBtnsActive = (emblaApi, prevBtn, nextBtn) => {
                const togglePrevNextBtnsState = () => {
                    if (emblaApi.canScrollPrev()) prevBtn.removeAttribute('disabled')
                    else prevBtn.setAttribute('disabled', 'disabled')

                    if (emblaApi.canScrollNext()) nextBtn.removeAttribute('disabled')
                    else nextBtn.setAttribute('disabled', 'disabled')
                }

                emblaApi
                    .on('select', togglePrevNextBtnsState)
                    .on('init', togglePrevNextBtnsState)
                    .on('reInit', togglePrevNextBtnsState)

                return () => {
                    prevBtn.removeAttribute('disabled')
                    nextBtn.removeAttribute('disabled')
                }
            }

            const addPrevNextBtnsClickHandlers = (emblaApi, prevBtn, nextBtn) => {
                const scrollPrev = () => {
                    emblaApi.scrollPrev()
                }
                const scrollNext = () => {
                    emblaApi.scrollNext()
                }
                prevBtn.addEventListener('click', scrollPrev, false)
                nextBtn.addEventListener('click', scrollNext, false)

                const removeTogglePrevNextBtnsActive = addTogglePrevNextBtnsActive(
                    emblaApi,
                    prevBtn,
                    nextBtn
                )

                return () => {
                    removeTogglePrevNextBtnsActive()
                    prevBtn.removeEventListener('click', scrollPrev, false)
                    nextBtn.removeEventListener('click', scrollNext, false)
                }
            }

            const setupProgressBar = (emblaApi, progressNode) => {
                const applyProgress = () => {
                    const progress = Math.max(0, Math.min(1, emblaApi.scrollProgress()))
                    progressNode.style.transform = `translate3d(${progress * 100}%,0px,0px)`
                }

                const removeProgress = () => {
                    progressNode.removeAttribute('style')
                }

                return {
                    applyProgress,
                    removeProgress
                }
            }

            if (emblaApi) {
                const { applyProgress, removeProgress } = setupProgressBar(
                    emblaApi,
                    progressNode
                )

                const removePrevNextBtnsClickHandlers = addPrevNextBtnsClickHandlers(
                    emblaApi,
                    prevBtn,
                    nextBtn
                )

                emblaApi
                    .on('init', applyProgress)
                    .on('reInit', applyProgress)
                    .on('scroll', applyProgress)
                    .on('slideFocus', applyProgress)
                    .on('destroy', removeProgress)
                    .on('destroy', removePrevNextBtnsClickHandlers)
            }
        })

    </script>



</x-layouts.layout>