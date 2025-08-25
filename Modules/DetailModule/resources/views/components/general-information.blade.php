<div class="general-information-wrapper p-4 lg:block hidden lg:block sm:w-full">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-semibold product-name">{{ $type === 'product' ? $data->name : $data->combo_name }}</h2>
        <livewire:detailmodule::components.wish-list :data="$data" />
    </div>

    <div class="public-information grid grid-cols-2 gap-y-2 gap-x-4">
        <div class="text-sm">
            <span>Nhà cung cấp: </span>
            <a href="#" class="text-blue-500">Cty Văn Hóa Minh Lâm</a>
        </div>
        <div class="text-sm">
            <span>Nhà xuất bản: </span>
            <span class="font-bold inline-block max-w-[200px] truncate align-bottom">
                @if ($type === "product")
                    {{ $data->publisher->publisher_name }}
                @elseif ($type === "combo")
                    {{ $data->productSkus->pluck('product.publisher.publisher_name')->unique()->implode(', ') }}
                @endif
            </span>
        </div>

        <div class="text-sm">
            <span>Tác giả:</span>
            <span class="font-bold inline-block max-w-[200px] truncate align-bottom">
                @if ($type === "product")
                    {{ $data->author }}
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

        <livewire:detailmodule::components.product-rating :data="$data" :type="$type" />

        <livewire:detailmodule::components.product-saled :data="$data" :type="$type" />
    </div>
    <livewire:detailmodule::components.flashsale :type="$type" :sku-id="$data->productSkus->first()->id" />
    <livewire:detailmodule::components.price :data="$data" :type="$type" />






</div>