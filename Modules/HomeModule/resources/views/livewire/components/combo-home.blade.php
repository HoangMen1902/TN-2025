<div class="container max-w-[1200px] mx-auto bg-white rounded-lg mb-6 shadow-md">
    <div id="categorytab-sachcombo11" class="px-6 py-4">
        <!-- Header -->
        <div
            class="flex items-center mb-6 bg-gradient-to-r from-blue-100 via-white to-white rounded-lg shadow px-5 py-3 border-l-4 border-blue-500">
            <div class="flex items-center justify-center bg-blue-500 rounded-full p-2 mr-4">
                <img src="https://cdn1.fahasa.com/media/wysiwyg/Thang-11-2023/icon_new.png" alt="New Icon"
                    class="h-6 w-6">
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Combo sản phẩm</h3>
        </div>


        <div class="relative overflow-visible">
            <div
        class="combo-button-prev absolute top-1/2 -left-6 z-10 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow w-10 aspect-square flex justify-center items-center cursor-pointer hover:bg-gray-100 transition-all duration-300">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </div>

    <div
        class="combo-button-next absolute top-1/2 -right-6 z-10 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow w-10 h-10 flex justify-center items-center cursor-pointer hover:bg-gray-100">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </div>
            <div class="swiper combo-swiper px-8">

                <div class="swiper-wrapper">
                    @forelse ($combos as $combo)
                        <div class="swiper-slide">
                            <a href="/chi-tiet-combo/{{ $combo->slug }}" class="block group">
                                <div
                                    class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100">
                                    <div class="overflow-hidden">
                                        @if (!empty($combo->images) && is_array($combo->images))
                                            <img src="{{ asset('storage/' . $combo->images[0]) }}"
                                                class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300"
                                                alt="{{ $combo->combo_name }}">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" class="w-full h-56 object-cover"
                                                alt="Placeholder">
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                            {{ $combo->combo_name }}
                                        </h3>
                                        <div class="flex items-center mt-2">
                                            <p class="text-red-600 font-bold text-sm">
                                                {{ number_format($combo->sale_price, 0, ',', '.') }}đ
                                            </p>
                                            @if ($combo->original_price > $combo->sale_price)
                                                <span
                                                    class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                                    -{{ round(($combo->original_price - $combo->sale_price) / $combo->original_price * 100) }}%
                                                </span>
                                            @endif
                                        </div>
                                        @if ($combo->original_price > $combo->sale_price)
                                            <p class="text-gray-400 text-xs line-through mt-0.5">
                                                {{ number_format($combo->original_price, 0, ',', '.') }}đ
                                            </p>
                                        @endif
                                        <div class="relative w-full h-3 bg-gray-200 rounded-full mt-3">
                                            <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full"
                                                style="width: 20%;"></div>
                                            <div
                                                class="absolute inset-0 flex items-center justify-center text-white text-[10px] leading-3">
                                                Đã bán 6
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-600 text-base col-span-5 text-center">Không tìm thấy combo sách nào.</p>
                    @endforelse
                </div>
            </div>
        </div>


        <div class="text-center mt-8">
            <a href="/combo"
                class="inline-block px-10 py-2 border-2 border-red-600 rounded-full text-red-600 font-bold hover:bg-red-600 hover:text-white transition-all duration-300">
                Xem Thêm
            </a>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.combo-swiper', {
                slidesPerView: 5,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.combo-button-next',
                    prevEl: '.combo-button-prev',
                },
                breakpoints: {
                    320: { slidesPerView: 1.2 },
                    480: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                    1280: { slidesPerView: 5 },
                },
                on: {
                    slideChange: function () {
                        const prevBtn = document.querySelector('.combo-button-prev');
                        const nextBtn = document.querySelector('.combo-button-next');


                        if (swiper.activeIndex > 0) {
                            prevBtn.classList.remove('hidden');
                        } else {
                            prevBtn.classList.add('hidden');
                        }


                        if (swiper.isEnd) {
                            nextBtn.classList.add('hidden');
                        } else {
                            nextBtn.classList.remove('hidden');
                        }
                    },
                }
            });

         
            const prevBtn = document.querySelector('.combo-button-prev');
            const nextBtn = document.querySelector('.combo-button-next');

            if (swiper.isBeginning) prevBtn.classList.add('hidden');
            if (swiper.isEnd) nextBtn.classList.add('hidden');
        });
    </script>
</div>