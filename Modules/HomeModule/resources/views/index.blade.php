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
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false"
                                aria-label="Slide 2" data-carousel-slide-to="1"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false"
                                aria-label="Slide 3" data-carousel-slide-to="2"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false"
                                aria-label="Slide 4" data-carousel-slide-to="3"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false"
                                aria-label="Slide 5" data-carousel-slide-to="4"></button>
                        </div>
                        <!-- Slider controls -->
                        <button type="button"
                            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-prev>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30  group-hover:bg-white/50  group-focus:ring-4 group-focus:ring-white  group-focus:outline-none">
                                <svg class="w-4 h-4 text-white  rtl:rotate-180" aria-hidden="true"
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
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30  group-hover:bg-white/50  group-focus:ring-4 group-focus:ring-white  group-focus:outline-none">
                                <svg class="w-4 h-4 text-white  rtl:rotate-180" aria-hidden="true"
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

        <livewire:homemodule.components.flash-sale />
        <div class=" bg-light p-2 my-6 rounded">
            <livewire:homemodule::components.product-category :childCategories="$childCategories">
            </livewire:homemodule::components.product-category :childCategories="$childCategories">
        </div>

        
        <livewire:homemodule::components.publisher-product />
        <livewire:combomodule::component.combo/>
        <livewire:suggest-products />

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const emblaNode = document.querySelector('.product')
                const viewportNode = emblaNode.querySelector('.product__viewport')
                const prevBtn = emblaNode.querySelector('.product__button--prev')
                const nextBtn = emblaNode.querySelector('.product__button--next')
                const progressNode = emblaNode.querySelector('.product__progress__bar')

                const OPTIONS = {
                    dragFree: true,
                    containScroll: 'trimSnaps'
                }

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
                    const {
                        applyProgress,
                        removeProgress
                    } = setupProgressBar(
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
