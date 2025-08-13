<x-layouts.layout>
    <div class="container max-w-[1200px] mx-auto">
        <!-- Banner Section -->
        <div class="flex flex-col md:flex-row h-auto md:h-[330px] my-3 gap-2">
            <!-- Carousel -->
            <div class="w-full md:w-4/6 h-[200px] md:h-full">
                <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                    <div id="indicators-carousel" class="relative w-full h-full" data-carousel="static">
                        <div class="relative h-full overflow-hidden rounded-lg">
                            <!-- Slide Items -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/muasamkhongtienmat_840x320T525.png"
                                    class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="Banner 1">
                            </div>
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="https://cdn1.fahasa.com/media/magentothem/banner7/CTT5_Resize1505_840x320.png"
                                    class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                    alt="Banner 2">
                            </div>
                            <!-- Add more slides if needed -->
                        </div>
                        <!-- Indicators -->
                        <div class="absolute z-30 flex -translate-x-1/2 space-x-3 bottom-5 left-1/2">
                            <button type="button" class="w-3 h-3 rounded-full" data-carousel-slide-to="0"></button>
                            <button type="button" class="w-3 h-3 rounded-full" data-carousel-slide-to="1"></button>
                        </div>
                        <!-- Controls -->
                        <button type="button"
                            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4"
                            data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 6 10" fill="none">
                                    <path d="M5 1 1 5l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>
                        <button type="button"
                            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4"
                            data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 6 10" fill="none">
                                    <path d="m1 9 4-4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Side Banners -->
            <div class="w-full md:w-2/6 flex flex-col md:justify-between gap-2 md:gap-0">
                <div class="h-1/2 md:h-[49%]">
                    <img class="w-full h-full object-cover rounded-lg shadow-lg"
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/homecreditT5_392x156_5.png"
                        alt="Image 1">
                </div>
                <div class="h-1/2 md:h-[49%]">
                    <img class="w-full h-full object-cover rounded-lg shadow-lg"
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/muasamkhongtienmatT5_392x156.png"
                        alt="Image 2">
                </div>
            </div>
        </div>
        <a href="/mini-game" class="fixed bottom-4 right-4 z-30 animate-bounce">
            <div class="relative group">
                <img src="{{ asset('assets/images/minigame.png') }}" alt="Mini game"
                    class="w-[120px] h-auto group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 rounded-full animate-ping bg-yellow-200 opacity-30 z-[-1]"></div>
            </div>
        </a>
        <!-- Grid Banners -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 my-3 h-[205px]">
            <div class=""><a href="#"><img
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Freeship_t5_310x210.png"
                        class="rounded block w-full h-full object-cover" alt=""></a></div>
            <div class=""><a href="#"><img
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/TrangQuaTang_T5_310x210_1.png"
                        class="rounded block w-full h-full object-cover" alt=""></a></div>
            <div class=""><a href="#"><img
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/Mcbooks_small_310x210.png"
                        class="rounded block w-full h-full object-cover" alt=""></a></div>
            <div class=""><a href="#"><img
                        src="https://cdn1.fahasa.com/media/wysiwyg/Thang-05-2025/MayTinh_T5_310X210.png"
                        class="rounded block w-full h-full object-cover" alt=""></a></div>
        </div>

        <!-- Icon Menu Grid -->
        <div class="grid grid-cols-10 gap-2 bg-light p-2 rounded">
            <div class="p-2 flex flex-col items-center justify-between h-[100px]">
                <div class="h-[50px] aspect-square">
                    <a href="">
                        <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-08-2025/Icon_158_120x120.png"
                            class="rounded block w-full h-full object-cover" alt="Banner 1"></a>
                </div>
                <div class="text-center">
                    <p class="text-sm">15.08</p>
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

        <!-- Livewire Components -->
        <livewire:homemodule::components.flashsale-product />

        <div class="bg-white md:bg-light p-2 my-6 rounded">
            <livewire:homemodule::components.product-category :childCategories="$childCategories" />
        </div>

        <livewire:homemodule::components.publisher-product />
        <livewire:homemodule::components.combo-home />
        <livewire:suggestmodule::suggest :isMobile="request()->header('User-Agent') &&
            preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent'))" />

    </div>
</x-layouts.layout>

<style>
    @media (max-width: 767px) {
        .bg-light {
            background-color: #f3f4f6;
        }
    }
</style>
