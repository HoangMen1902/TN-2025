<div class="rounded-t-[20px] overflow-hidden my-6">
    <div class="w-full" id="personalization-section">
        <div class="block md:hidden bg-[#e6f4ea] py-6">
            <h2 class="text-base sm:text-lg font-bold text-green-700 text-center">
                Gợi ý dành riêng cho bạn
            </h2>
        </div>
        <div class="hidden md:block bg-top bg-cover bg-no-repeat"
            style="background-image: url('https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/banner_personalization.png');">
            <div class="mx-auto w-full max-w-[1400px] px-4 pt-20 pb-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($displayedProducts->slice(0, $isMobile ? 6 : 5) as $product)
                        @include('suggestmodule::livewire.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-b-[20px] px-4 pb-6">
        <div class="mx-auto w-full max-w-[1400px]">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4">
                @foreach ($displayedProducts->slice($isMobile ? 6 : 5) as $product)
                    @include('suggestmodule::livewire.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="text-center mt-6">
                @if ($canLoadMore)
                    <button wire:click="loadMore"
                        class="inline-block px-6 sm:px-10 py-2 border-2 border-red-600 rounded-full text-red-600 font-bold hover:bg-red-600 hover:text-white transition duration-300 cursor-pointer">
                        Xem thêm
                    </button>
                @else
                    <button wire:click="collapse"
                        class="inline-block px-6 sm:px-10 py-2 border-2 border-red-600 rounded-full text-red-600 font-bold hover:bg-red-600 hover:text-white transition duration-300 cursor-pointer">
                        Rút gọn
                    </button>
                @endif
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 767px) {
            #personalization-section {
                background-color: #e6f4ea;
                background-image: none !important;
            }
        }

        @media (min-width: 768px) {
            #personalization-section {
                background-image: url('https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/banner_personalization.png');
                background-position: top;
                background-size: cover;
                background-repeat: no-repeat;
            }
        }
    </style>
</div>
