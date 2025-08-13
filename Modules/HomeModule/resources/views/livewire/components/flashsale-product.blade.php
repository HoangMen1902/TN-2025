<section class="my-5">
    @if($products)
        <div class="bg-blue-300 py-6 px-4 border-none rounded">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex items-center bg-white h-16 border-none rounded-[10px] justify-between mb-6">
                    <div class="flex items-center gap-4 p-[20px]">
                        <img class="max-w-[145px] w-100" src="{{ asset('modules/homemodule/img/image.png') }}" alt="">
                        <div class="text-black text-base">Kết thúc trong</div>
                        <div id="countdown" class="flex items-center space-x-1 text-white font-semibold">
                            <div id="hours" class="bg-black px-2 py-1 rounded">00</div>
                            <div class="text-black">:</div>
                            <div id="minutes" class="bg-black px-2 py-1 rounded">00</div>
                            <div class="text-black">:</div>
                            <div id="seconds" class="bg-black px-2 py-1 rounded">00</div>
                        </div>
                    </div>
                    <a href="#" class="text-blue-500 font-semibold hover:underline p-[20px]">Xem tất cả →</a>
                </div>

                <!-- Swiper -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($products as $product)
                            <div class="swiper-slide">
                                <div class="bg-white p-4 shadow-sm rounded-[10px] h-full flex flex-col ">
                                    <img src="{{ asset('storage/' . $product['image']) }}" alt="Sản phẩm flashsale"
                                        class="w-full aspect-[4/3] object-contain mx-auto">
        <h3 class="text-sm mt-2 font-medium leading-5 line-clamp-2"
            style="min-height: calc(1.25rem * 2);">
            {{ $product['name'] }}
        </h3>
                                    <div class="text-red-600 font-bold text-lg mt-1">
                                        {{ number_format($product['sale_price'], 0, '', '.') }} đ
                                        <span class="bg-red-500 text-white text-xs font-semibold px-1 py-0.5 rounded ml-1">
                                            -{{ $product['discount_percent'] }}%
                                        </span>
                                    </div>
                                    <div class="text-gray-400 text-sm line-through">
                                        {{ number_format($product['price'], 0, '', '.') }} đ
                                    </div>

                                    @php
                                        $sku = $product['sku'] ?? null;
                                        $sold = $product['sold'] ?? 0;
                                        $total = $product['total'] ?? 1;
                                        $percentSold = $total > 0 ? round(($sold / $total) * 100) : 0;
                                    @endphp

                                    <div class="relative w-full h-4 bg-gray-300 rounded-full overflow-hidden mt-2">
                                        <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full"
                                            style="width: {{ $percentSold }}%;"></div>
                                        <div class="absolute w-full text-center text-white text-xs leading-4">
                                            {{ $percentSold }}% đã bán
                                        </div>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-600">
                                        Còn lại {{ $total - $sold }} sản phẩm
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-end mt-4 space-x-2">
                        <div class="swiper-button-prev cursor-pointer text-blue-700 text-sm w-10 h-10 p-6 shadow border-none rounded-full bg-white flex items-center justify-center">
                            <span class="text-[40px] w-20">‹</span>
                        </div>
                        <div class="swiper-button-next cursor-pointer text-blue-700 text-sm w-10 h-10 p-6 shadow border-none rounded-full bg-white flex items-center justify-center">
                            <span class="text-[40px] w-20">›</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countdown + Swiper Script -->
        @if ($expiredAt)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const countdownDate = new Date("{{ $expiredAt }}").getTime();

                    const countdownInterval = setInterval(() => {
                        const now = new Date().getTime();
                        const distance = countdownDate - now;

                        if (distance <= 0) {
                            clearInterval(countdownInterval);
                            document.getElementById("hours").textContent = "00";
                            document.getElementById("minutes").textContent = "00";
                            document.getElementById("seconds").textContent = "00";
                            return;
                        }

                        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                        const minutes = Math.floor((distance / (1000 * 60)) % 60);
                        const seconds = Math.floor((distance / 1000) % 60);

                        document.getElementById("hours").textContent = String(hours).padStart(2, '0');
                        document.getElementById("minutes").textContent = String(minutes).padStart(2, '0');
                        document.getElementById("seconds").textContent = String(seconds).padStart(2, '0');
                    }, 1000);

                    new Swiper(".mySwiper", {
                        slidesPerView: 1,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        breakpoints: {
                            480: {
                                slidesPerView: 2,
                                spaceBetween: 12
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 16
                            },
                            1024: {
                                slidesPerView: 5,
                                spaceBetween: 20
                            },
                        },
                    });
                });
            </script>
        @endif
    @endif
</section>
