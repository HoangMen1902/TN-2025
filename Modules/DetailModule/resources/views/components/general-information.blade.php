<div class="general-information-wrapper p-4 lg:block hidden lg:block sm:w-full">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-semibold">{{ $data->name }}</h2>
        @auth
            @php
                $isInWishlist = \App\Models\Wishlist::where('user_id', Auth::id())
                    ->where('product_id', $data->id)
                    ->exists();
            @endphp
            <form action="{{ route('wishlist.store') }}" method="POST" class="my-2 flex items-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $data->id }}">
                <button type="submit" class="flex items-center text-red-500 underline {{ $isInWishlist ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isInWishlist ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    {{ $isInWishlist ? 'Đã thêm vào yêu thích' : 'Thêm vào yêu thích' }}
                </button>
            </form>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="my-2 flex items-center text-red-500 underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                Đăng nhập để thêm vào yêu thích
            </a>
        @endguest
    </div>

    <div class="public-information grid grid-cols-2 gap-y-2 gap-x-4">
        <div class="text-sm">
            <span>Nhà cung cấp: </span>
            <a href="#" class="text-blue-500">Cty Văn Hóa Minh Lâm</a>
        </div>
        <div class="text-sm">
            <span>Nhà xuất bản: </span>
            <span class="font-bold">{{ $data->publisher->publisher_name }}</span>
        </div>
        <div class="text-sm">
            <span>Tác giả:</span>
            <span class="font-bold">{{ $data->author }}</span>
        </div>
        <div class="text-sm">
            <span>Hình thức bìa:</span>
            <span class="font-bold">{{ $data->book_cover }}</span>
        </div>
    </div>
    <div class="rating-box flex py-[8px]">
        <a href="#" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <span class="text-amber-400 text-sm ml-[4px]">(0 đánh giá)</span>
        </a>
        <span class="text-sm mx-1">|</span>
        <span class="text-sm font-thin">Đã bán</span>
        <span class="text-sm font-bold ml-0.5">100</span>
    </div>
    <livewire:detailmodule::components.flashsale :data="$data"></livewire:detailmodule::components.flashsale>
    <livewire:detailmodule::components.price :data="$data"></livewire:detailmodule::components.price>
</div>