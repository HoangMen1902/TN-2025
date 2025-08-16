<div>
    <div id="notificationTabContent" class="p-4">
        <div class="tab-content" id="all">
            @if ($wishLists->isEmpty())
                <p class="text-gray-500">Chưa có sản phẩm nào trong danh sách yêu thích.</p>
            @else
                <div
                    class="space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @foreach ($wishLists as $wishList)
                        @if ($wishList->product)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex items-center mb-3">
                                    <h2 class="text-sm font-semibold text-gray-800 uppercase">
                                        {{ $wishList->product->categories->pluck('name')->join(', ') ?: 'Không có danh mục' }}
                                    </h2>
                                </div>
                                <hr class="border-gray-200 mb-3">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 object-contain"
                                            src="{{ $wishList->product->thumbnail ? asset('storage/' . $wishList->product->thumbnail) : 'https://via.placeholder.com/150' }}"
                                            alt="{{ $wishList->product->name }}">
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-800 mb-1">
                                            {{ $wishList->product->name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-800 mb-1">
                                            Giá:
                                            @if ($wishList->product->productSkus->isNotEmpty())
                                                {{ number_format($wishList->product->productSkus->first()->sale_price ?? $wishList->product->productSkus->first()->price, 0, ',', '.') }}
                                                ₫
                                            @else
                                                Chưa có giá
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Thêm vào yêu thích: {{ $wishList->created_at->format('d/m/Y - H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <button wire:click="removeFromWishlist({{ $wishList->product_id }})"       
                                            class="text-red-500 hover:text-red-600 focus:outline-none" title="Xóa khỏi yêu thích">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12M3 7h18" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{ $wishLists->links() }}
            @endif
        </div>
    </div>
</div>



