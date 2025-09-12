
<div class="bg-white rounded-lg shadow-md p-4 relative">
    <div class="flex items-center mb-4">
        <img src="https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_menu_red.svg"
             class="h-5 w-5 mr-2" alt="Logo danh mục">
        <p class="text-xl font-bold text-gray-800">Danh mục sản phẩm</p>
    </div>
    <div class="relative">
        <div class="swiper-button-prev-category absolute -left-3 top-1/2 -translate-y-1/2 z-10 bg-white shadow border rounded-full w-10 h-10 flex items-center justify-center cursor-pointer">
            <span class="text-2xl text-blue-600">‹</span>
        </div>
        <div class="swiper-button-next-category absolute -right-3 top-1/2 -translate-y-1/2 z-10 bg-white shadow border rounded-full w-10 h-10 flex items-center justify-center cursor-pointer">
            <span class="text-2xl text-blue-600">›</span>
        </div>
        <div class="swiper category-swiper px-2">
            <div class="swiper-wrapper">
                @foreach ($childCategories as $category)
                    <div class="swiper-slide">
                        <a href="/san-pham?category={{ $category->id }}"
                           class="group p-2 flex flex-col items-center justify-between rounded hover:bg-gray-50 transition-all duration-200 w-full max-w-[130px] mx-auto">
                            <div class="w-[100px] h-[100px] flex items-center justify-center">
                                <img src="{{ $category->products->isNotEmpty() && $category->products->first()->thumbnail
                                    ? asset('storage/' . $category->products->first()->thumbnail)
                                    : 'https://via.placeholder.com/150' }}"
                                     alt="{{ $category->name }}"
                                     class="h-full w-auto object-contain rounded transition-transform duration-200 group-hover:scale-105">
                            </div>
                            <div class="text-center mt-2">
                                <p class="text-sm font-medium text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $category->name }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.category-swiper', {
            slidesPerView: 2,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next-category',
                prevEl: '.swiper-button-prev-category',
            },
            breakpoints: {
                480: { slidesPerView: 3 },
                640: { slidesPerView: 4 },
                768: { slidesPerView: 5 },
                1024: { slidesPerView: 6 },
                1280: { slidesPerView: 8 },
            },
        });
    });
</script>
</div>
