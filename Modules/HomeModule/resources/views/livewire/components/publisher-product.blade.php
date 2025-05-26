@if (isset($data))
    <div class="bg-white rounded mb-6">
        <div class="mb-4 border-b border-gray-200 px-3">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                data-tabs-toggle="#default-tab-content" role="tablist">
                @foreach ($data as $index => $publisher)
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block  p-4 border-b-2 rounded-t-lg {{ $index === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent' }}"
                            id="tab-{{ $publisher->id }}" data-tabs-target="#publisher-{{ $publisher->id }}" type="button"
                            role="tab" aria-controls="publisher-{{ $publisher->id }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ $publisher->publisher_name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="default-tab-content">
            @foreach ($data as $index => $publisher)
                <div class="{{ $index === 0 ? '' : 'hidden' }} p-4 rounded-lg" id="publisher-{{ $publisher->id }}"
                    role="tabpanel" aria-labelledby="tab-{{ $publisher->id }}">
                    <div class="product-holder grid grid-cols-5 gap-4">
                        @foreach ($publisher->products as $product)
                        @php
                        $percent = (($product->productSkus->first()->price - $product->productSkus->first()->sale_price)/$product->productSkus->first()->price) * 100
                        @endphp
                            <a href="/chi-tiet/{{$product->slug}}">
                                <div
                                    class="product-card w-full h-[360px] flex flex-col justify-between cursor-pointer p-2 bg-white hover:shadow rounded">
                                    {{-- Nội dung trên --}}
                                    <div class="flex flex-col gap-2">
                                        <div class="product-img w-full">
                                            <img class="w-full max-h-[200px] min-h-[160px] object-contain"
                                                src="{{asset('storage/' . $product->thumbnail)}}" alt="">
                                        </div>

                                        <div class="product-name min-h-[40px] line-clamp-2">
                                            <span class="font-medium text-sm block">{{$product->name}}</span>
                                        </div>

                                        <div class="product-info">
                                            <div class="product-price flex flex-col text-sm">
                                                <div class="flex items-center ">
                                                    <span class="text-red-600 text-lg font-bold">
                                                        {{ number_format($product->productSkus->first()->sale_price, 0, '', '.') }} đ
                                                    </span>
        
                                                    <div class="bg-red-500 text-white text-xs font-semibold  px-1 py-0.5 rounded ml-2 mt-3">{{ '-' . round($percent, 2) . '%' }}</div>
                                                </div>
                                                <span class="text-gray-400 line-through">
                                                    {{ number_format($product->productSkus->first()->price, 0, '', '.') }} đ
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative w-full h-4 bg-gray-300 rounded-full overflow-hidden">
                                        <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;"></div>
                                        <div class="absolute w-full text-center text-white text-xs leading-4">Đã bán 6</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>


                </div>
            @endforeach
        </div>
    </div>

@else
<div>
    
</div>
@endif