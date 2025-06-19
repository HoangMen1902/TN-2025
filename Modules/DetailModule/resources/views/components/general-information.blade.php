<div class="general-information-wrapper p-4 lg:block hidden lg:block sm:w-full">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-semibold product-name">{{ $type === 'product' ? $data->name : $data->combo_name }}</h2>
        <livewire:detailmodule::components.wish-list :data="$data"/>
    </div>

    <div class="public-information grid grid-cols-2 gap-y-2 gap-x-4">
        <div class="text-sm">
            <span>Nhà cung cấp: </span>
            <a href="#" class="text-blue-500">Cty Văn Hóa Minh Lâm</a>
        </div>
        <div class="text-sm">
            <span>Nhà xuất bản: </span>
            <span class="font-bold">
                @if ($type === "product")
                    {{$data->publisher->publisher_name}}
                @elseif ($type === "combo")
                    {{ $data->productSkus->pluck('product.publisher.publisher_name')->unique()->implode(', ') }}
                @endif
            </span>
        </div>
        <div class="text-sm">
            <span>Tác giả:</span>
            <span class="font-bold">
                @if ($type === "product")
                    {{$data->author}}
                @elseif ($type === "combo")
                    {{ $data->productSkus->pluck('product.author')->unique()->implode(', ') }}
                @endif
            </span>
        </div>
        <div class="text-sm">
            <span>Hình thức bìa:</span>
            <span class="font-bold">
                @if ($type === "product")
                    {{$data->book_cover}}
                @elseif ($type === "combo")
                    {{ $data->productSkus->pluck('product.book_cover')->unique()->implode('/ ') }}
                @endif
            </span>
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
<livewire:detailmodule::components.flashsale :sku-id="$data->productSkus->first()->id" />
<livewire:detailmodule::components.price :data="$data" :type="$type"/>






</div>