<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Sản phẩm yêu thích</h1>
                </div>

                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            <li role="presentation">
                                <button class="inline-block text-red-500 p-4 border-b-2 border-red-500 rounded-t-lg"
                                    id="all-tab" type="button" role="tab" aria-controls="all" aria-selected="true">
                                    Tất cả
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div id="notificationTabContent" class="p-4">
                    <div class="tab-content" id="all">
                        @if (!isset($wishlists) || $wishlists->isEmpty())
                            <p class="text-gray-500">Chưa có sản phẩm nào trong danh sách yêu thích.</p>
                        @else
                            <div
                                class="space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                @foreach ($wishlists as $wishlist)
                                    @if ($wishlist->product)
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center mb-3">
                                                <h2 class="text-sm font-semibold text-gray-800 uppercase">
                                                    {{ $wishlist->product->categories->pluck('name')->join(', ') ?: 'Không có danh mục' }}
                                                </h2>
                                            </div>

                                            <hr class="border-gray-200 mb-3">

                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-16 w-16 object-contain"
                                                        src="{{ $wishlist->product->thumbnail ? asset('storage/' . $wishlist->product->thumbnail) : 'https://via.placeholder.com/150' }}">
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-gray-800 mb-1">
                                                        {{ $wishlist->product->name }}
                                                    </p>
                                                    <p class="text-sm font-medium text-gray-800 mb-1">
                                                        Giá:
                                                        @if ($wishlist->product->productSkus->isNotEmpty())
                                                            {{ number_format($wishlist->product->productSkus->first()->sale_price ?? $wishlist->product->productSkus->first()->price, 0, ',', '.') }}
                                                            ₫
                                                        @else
                                                            Chưa có giá
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        Thêm vào yêu thích: {{ $wishlist->created_at->format('d/m/Y - H:i') }}
                                                    </p>
                                                </div>
                                                <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST"
                                                    class="flex items-center">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                                                    @if (Auth::check())
                                                        <input type="hidden" name="wishlist_id" value="{{ $wishlist->id }}">
                                                    @endif
                                                    <button type="submit" class="text-red-500 hover:text-red-600 focus:outline-none"
                                                        title="Xóa khỏi yêu thích">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12M3 7h18" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            {{ $wishlists->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thumb-gray-300 {
            scrollbar-color: #d1d5db #f3f4f6;
        }

        .scrollbar-track-gray-100 {
            background: #f3f4f6;
        }
    </style>
</x-layouts.layout>