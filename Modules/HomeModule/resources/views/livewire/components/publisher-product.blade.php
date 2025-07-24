<div>
@if (isset($data) && count($data))
    <div class="bg-white rounded mb-6">
        <div class="mb-4 border-b border-gray-200 px-3">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="publisher-tabs" role="tablist">
                @foreach ($data as $index => $publisher)
                    <li class="me-2" role="presentation">
                        <button
                            class="tab-button inline-block p-4 border-b-2 rounded-t-lg focus:outline-none transition-all duration-200
                                        {{ $index === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                            data-tab="#publisher-{{ $publisher->id }}" type="button" role="tab">
                            {{ $publisher->publisher_name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div id="publisher-tab-content">
            @foreach ($data as $index => $publisher)
                <div class="tab-pane {{ $index === 0 ? '' : 'hidden' }} p-4" id="publisher-{{ $publisher->id }}"
                    role="tabpanel">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($publisher->products as $product)
                            @php
                                $sku = $product->productSkus->first();
                                $sale = $sku->sale_price ?? 0;
                                $price = $sku->price ?? 0;
                                $hasSale = $sale && $sale < $price;
                                $percent = $hasSale ? round(($price - $sale) / $price * 100) : 0;
                                $thumbnailPath = $product->thumbnail ?? null;

                            @endphp
                                <a href="{{ $product->is_ebook ? route('ebooks.show', $product->id) : url('/chi-tiet/' . $product->slug) }}">
                                <div
                                    class="flex flex-col justify-between h-full bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition-all duration-300">
                                    <div>
                                        <div class="w-full overflow-hidden">
                                            <img src="{{ $product->is_ebook ? $thumbnailPath : asset('storage/' . $thumbnailPath) }}"
                                                class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition-transform duration-300">
                                            @if($product->is_ebook)
                                                <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">Ebook</span>
                                            @endif
                                        </div>
                                        <div class="p-3">
                                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                                {{ $product->name }}
                                            </h3>
                                            <div class="flex items-center mt-2">
                                                <p class="text-red-600 font-bold text-sm">
                                                    {{ number_format($hasSale ? $sale : $price, 0, ',', '.') }}đ
                                                </p>
                                                @if ($hasSale)
                                                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                                        -{{ $percent }}%
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-400 text-xs mt-0.5 line-through {{ $hasSale ? '' : 'invisible' }}">
                                                {{ number_format($price, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                    </div>
                                    <div class="px-3 pb-3">
                                        <div class="relative w-full h-3 bg-gray-200 rounded-full">
                                            <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;">
                                            </div>
                                            <div
                                                class="absolute inset-0 flex items-center justify-center text-white text-[10px] leading-3">
                                                Đã bán 6
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tabButtons = document.querySelectorAll('.tab-button');
                    const tabPanes = document.querySelectorAll('.tab-pane');

                    tabButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const target = document.querySelector(button.dataset.tab);
                            tabButtons.forEach(btn => {
                                btn.classList.remove('border-blue-500', 'text-blue-600');
                                btn.classList.add('border-transparent', 'text-gray-500');
                            });
                            tabPanes.forEach(pane => pane.classList.add('hidden'));
                            button.classList.add('border-blue-500', 'text-blue-600');
                            button.classList.remove('border-transparent', 'text-gray-500');
                            target.classList.remove('hidden');
                        });
                    });
                });
            </script>
        </div>
@else
        <div class="text-center py-4 text-gray-500">Không có dữ liệu nhà xuất bản.</div>
@endif
</div>