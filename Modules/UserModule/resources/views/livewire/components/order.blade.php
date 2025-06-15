<div class="">
    <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
        <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
            <h1 class="text-lg md:text-2xl font-medium">Đơn hàng của tôi</h1>
        </div>
        <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">
        <div class="mb-4 border-b border-gray-200 relative">
            <!-- Horizontal scrollable wrapper -->
            <div class="flex overflow-x-auto scrollbar-hide" id="orderTabs">
                <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('all')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'all' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Tất cả
                        </button>
                    </li>
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('pending-payment')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'pending-payment' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Chờ xử lý
                        </button>
                    </li>
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('paid')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'paid' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Đã thanh toán
                        </button>
                    </li>
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('shipping')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'shipping' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Vận chuyển
                        </button>
                    </li>
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('completed')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'completed' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Đã giao
                        </button>
                    </li>
                    <li class="mr-2">
                        <button wire:click="setStatusFilter('cancelled')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'cancelled' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Đã hủy
                        </button>
                    </li>
                    <li>
                        <button wire:click="setStatusFilter('refund')"
                            class="inline-block p-4 border-b-2 {{ $statusFilter === 'refund' ? 'border-red-500 text-red-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                            Hoàn tiền
                        </button>
                    </li>
                </ul>

            </div>


            <!-- Scroll indicator shadows - appear when content is scrollable -->
            <div
                class="absolute left-0 top-0 h-full w-4 bg-gradient-to-r from-white to-transparent pointer-events-none md:hidden">
            </div>
            <div
                class="absolute right-0 top-0 h-full w-4 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden">
            </div>
        </div>

        <div class="px-4 mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search" wire:model.live="search"
                    class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Bạn có thể tìm kiếm theo tên Shop, ID đơn hàng hoặc Tên Sản phẩm">

            </div>
        </div>

        <div id="orderTabContent">
            <!-- Tất cả -->
            @if (!is_null($orders) && $orders->isNotEmpty())
                @foreach ($orders as $order)

                    <div wire:key="order-{{ $order->id }}" class="block" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="border border-gray-200 rounded-lg mx-4 mb-4">
                            <div class="border-b border-gray-200 p-3 md:p-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <button class="bg-blue-100 text-red-500 px-2 py-1 rounded text-xs mr-3">Yêu thích</button>
                                    <span class="font-medium">
                                        {{ $order->orderDetails->first()?->sku?->product?->categories?->first()?->name ?? 'Đơn hàng' }}</span>
                                </div>

                            </div>

                            @foreach ($order->orderDetails as $detail)
                                <div class="p-3 md:p-4 border-b border-gray-200">
                                    <div class="flex items-start">
                                        <div class="w-14 h-14 md:w-16 md:h-16 mr-3 flex-shrink-0">
                                            <img src="{{ asset('storage/' . $detail->sku->images[0]) ?? '/default.jpg' }}"
                                                alt="Sản phẩm" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium mb-1 text-sm md:text-base line-clamp-2">
                                                {{ $detail->sku->product->name ?? 'Tên sản phẩm' }}
                                            </p>
                                            @if ($detail->sku && $detail->sku->option_values)
                                                <p class="text-gray-500 text-xs md:text-sm mb-1">
                                                    Phân loại hàng: {{ $detail->sku->option_values->pluck('value')->join(', ') }}
                                                </p>
                                            @endif

                                            <p class="text-gray-500 text-xs md:text-sm">x{{ $detail->quantity }}</p>
                                            @if($order->paymentDetail && $order->paymentDetail->tracking_id)
                                                <div class="text-sm text-gray-600">
                                                    Mã đơn: <span
                                                        class="font-medium">{{ $order->paymentDetail->tracking_id }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-right ml-2 flex-shrink-0">
                                            <div class="flex flex-col justify-end items-end gap-1">
                                                <span class="text-gray-500 text-xs">Combo Khuyến Mãi</span>
                                                <span class="text-gray-500 text-xs">Mua 2, Tiết kiệm ₫2.000</span>
                                            </div>
                                            <div class="text-red-500 font-medium text-sm md:text-base">
                                                ₫{{ number_format($detail->price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @php
                                $statusMessages = [
                                    'Đang xử lý' => [
                                        'label' => 'Đang xử lý',
                                        'color' => 'text-yellow-500',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <circle cx="12" cy="12" r="10" stroke-width="2" />
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            '
                                    ],
                                    'Đã thanh toán' => [
                                        'label' => 'Đã thanh toán',
                                        'color' => 'text-blue-500',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>'
                                    ],
                                    'Vận chuyển' => [
                                        'label' => 'Đang vận chuyển',
                                        'color' => 'text-indigo-500',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 12h18M5 16h14l-1-4H6l-1 4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>'
                                    ],
                                    'Chờ hoàn tiền' => [
                                        'label' => 'Chờ hoàn tiền',
                                        'color' => 'text-yellow-600',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4v4M8 8h8M12 12v4M8 16h8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>'
                                    ],
                                    'Đã hoàn tiền' => [
                                        'label' => 'Đã hoàn tiền',
                                        'color' => 'text-green-600',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>'
                                    ],
                                    'Đã giao' => [
                                        'label' => 'Giao hàng thành công',
                                        'color' => 'text-green-500',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>'
                                    ],
                                    'Đã hủy' => [
                                        'label' => 'Đã hủy',
                                        'color' => 'text-red-500',
                                        'icon' => '<svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><line x1="18" y1="6" x2="6" y2="18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="6" y1="6" x2="18" y2="18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line></svg>'
                                    ],
                                ];

                                $status = $order->orders_status;
                                $statusInfo = $statusMessages[$status] ?? ['label' => 'Không rõ trạng thái', 'color' => 'text-gray-500', 'icon' => ''];
                                $total_price = $order->orderDetails->sum(function ($detail) {
                                    return $detail->price * $detail->quantity;
                                });
                            @endphp

                            <div class="p-3 md:p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                                <div class="flex items-center">
                                    {!! $statusInfo['icon'] !!}
                                    <span class="{{ $statusInfo['color'] }} text-sm">{{ $statusInfo['label'] }}</span>
                                </div>
                                <!-- Phần thành tiền hay khác -->
                                <div>
                                    <div class="text-gray-600 text-sm text-right">
                                        Thành tiền: <span class="text-red-500 text-base md:text-lg font-medium">
                                            ₫{{ number_format($total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <!-- Thêm tracking_id, shipment_price, total_price ở đây -->

                                    @if($order->shipment_price)
                                        <div class="text-sm text-gray-600">
                                            Phí vận chuyển: <span
                                                class="font-medium text-blue-600">₫{{ number_format($order->shipment_price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($order->total_price)
                                        <div class="text-sm text-gray-600">
                                            Tổng tiền: <span
                                                class="font-medium text-green-600">₫{{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            @php
                                $status = $order->orders_status;
                                $reviewDeadline = $order->updated_at->addDays(5)->format('d-m-Y');
                            @endphp

                            <div class="p-3 md:p-4 border-t border-gray-200 flex flex-wrap gap-2 justify-end">
                                @if($status === 'Đang xử lý')
                                    <div class="text-sm text-gray-700">
                                        Hiện tại chúng tôi đang kiểm tra và sẽ xử lý đơn hàng sớm nhất.
                                    </div>
                                @elseif($status === 'Đã thanh toán')
                                    <div class="text-sm text-blue-600">
                                        Bạn đã thanh toán và đơn hàng của bạn sẽ được chuẩn bị để vận chuyển.
                                    </div>
                                @elseif($status === 'Vận chuyển')
                                    <div class="text-sm text-indigo-600">
                                        Đơn hàng đang được vận chuyển đến bạn, vui lòng chờ nhận hàng.
                                    </div>
                                @elseif($status === 'Chờ hoàn tiền')
                                    <div class="text-sm text-yellow-600">
                                        Đơn hàng đang chờ xử lý hoàn tiền, xin vui lòng chờ.
                                    </div>
                                @elseif($status === 'Đã hoàn tiền')
                                    <div class="text-sm text-green-600">
                                        Đơn hàng đã được hoàn tiền thành công.
                                    </div>
                                @elseif($status === 'Đã giao')
                                    <div class="flex flex-col md:flex-row gap-2 items-center">
                                        <div class="text-xs md:text-sm text-gray-500">
                                            Đánh giá sản phẩm trước {{ $reviewDeadline }}
                                        </div>

                                    </div>
                                @elseif($status === 'Đã hủy')
                                    <div class="text-sm text-red-500">
                                        Đơn hàng đã bị hủy, nếu có thắc mắc vui lòng liên hệ hỗ trợ.
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">
                                        Trạng thái đơn hàng không xác định.
                                    </div>
                                @endif
                            </div>


                            @php
                                $product = $order->orderDetails->first()?->sku?->product;
                            @endphp

                            <div class="p-3 md:p-4 border-t border-gray-200 flex flex-wrap gap-2 justify-end">
                                @if (in_array($order->orders_status, ['Đang xử lý', 'Đã thanh toán']))
                                    <button wire:click="openCancelModal({{ $order->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Hủy đơn
                                    </button>

                                @elseif ($order->orders_status === 'Vận chuyển')
                                    <button
                                        class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Liên hệ người bán
                                    </button>

                                @elseif ($order->orders_status === 'Đã giao')
                                    <button wire:click="requestReturn({{ $order->id }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Yêu cầu trả hàng
                                    </button>
                                    <button wire:click="openRatingModal({{ $order->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Đánh giá
                                    </button>
                                    <a href="{{ url('/chi-tiet/' . $product->slug) }}"
                                        class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Mua lại
                                    </a>
                                    @livewire('pdf-order', ['orderId' => $order->id])
                                @elseif ($order->orders_status === 'Đã hủy' && $product)
                                    <a href="{{ url('/chi-tiet/' . $product->slug) }}"
                                        class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">
                                        Mua lại
                                    </a>

                                @elseif (in_array($order->orders_status, ['Chờ hoàn tiền', 'Đã hoàn tiền']))
                                    <span class="text-sm text-gray-500 italic">Đơn hàng đang xử lý hoàn tiền</span>
                                @endif
                            </div>


                        </div>
                    </div>
                    @if ($showRatingModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30">
                            <div class="w-full max-w-xl bg-white rounded-lg shadow max-h-[100vh] overflow-y-auto">
                                <div class="p-4 border-b rounded-t flex justify-between items-center">
                                    <h3 class="text-xl font-semibold text-gray-900">Đánh giá sản phẩm</h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                        wire:click="$set('showRatingModal', false)">
                                        <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Đóng</span>
                                    </button>
                                </div>
                                <div class="p-6 space-y-6">
                                    @php
                                        $order = $orders->find($selectedOrderId);
                                    @endphp
                                    @foreach ($order->orderDetails as $detail)
                                        <div class="mb-6 border-b pb-4">
                                            <!-- Thông tin sản phẩm -->
                                            <div class="flex items-start border-b border-gray-200 pb-4">
                                                <div class="w-14 h-14 md:w-16 md:h-16 mr-3 flex-shrink-0">
                                                    <img src="{{ $detail->sku->images[0] ?? '/default.jpg' }}" alt="Sản phẩm"
                                                        class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium mb-1 text-sm md:text-base line-clamp-2">
                                                        {{ $detail->sku->product->name ?? 'Tên sản phẩm' }}
                                                    </p>
                                                    @if ($detail->sku && $detail->sku->option_values)
                                                        <p class="text-gray-500 text-xs md:text-sm mb-1">
                                                            Phân loại hàng: {{ $detail->sku->option_values->pluck('value')->join(', ') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="space-y-4 mt-2">
                                                <!-- Đánh giá sao -->
                                                <div class="text-center">
                                                    <p class="text-gray-700 mb-2">Chất lượng sản phẩm</p>
                                                    <div class="flex items-center justify-center space-x-1 mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <button type="button" wire:click="set('ratings.{{ $detail->id }}', {{ $i }})"
                                                                class="{{ ($ratings[$detail->id] ?? 5) >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 text-xl">
                                                                ★
                                                            </button>
                                                        @endfor
                                                        @error('ratings.' . $detail->id) <span
                                                        class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <!-- Nội dung đánh giá -->
                                                <div>
                                                    <textarea wire:model="comments.{{ $detail->id }}" rows="4"
                                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-red-500 focus:border-red-500"
                                                        placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..."></textarea>
                                                    @error('comments.' . $detail->id) <span
                                                    class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <!-- Upload ảnh -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đánh giá (tối đa 3
                                                        ảnh):</label>
                                                    <label
                                                        class="flex flex-row items-center gap-1 w-30 px-1 py-1 h-12 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue-100 hover:text-blue-600 transition-all duration-150">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4">
                                                            </path>
                                                        </svg>
                                                        <span class=" text-sm leading-normal">Chọn ảnh</span>
                                                        <input type="file" multiple accept="image/*"
                                                            wire:model="images.{{ $detail->id }}" class="hidden" />
                                                    </label>
                                                    @if (!empty($images[$detail->id]))
                                                        <div class="flex mt-2 gap-2">
                                                            @foreach ($images[$detail->id] as $img)
                                                                <div class="relative group">
                                                                    <img src="{{ $img->temporaryUrl() }}"
                                                                        class="w-16 h-16 object-cover rounded border" />
                                                                    <button type="button"
                                                                        wire:click="removeImage({{ $detail->id }}, {{ $loop->index }})"
                                                                        class="absolute top-0 right-0 text-black rounded-full p-1 opacity-70 hover:opacity-100 transition text-2xl leading-none">
                                                                        &times;
                                                                    </button>

                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @endif
                                                    @error('images.' . $detail->id) <span
                                                    class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <!-- Đánh giá ẩn danh -->
                                                <div class="flex items-center">
                                                    <input type="checkbox" wire:model="anonymous.{{ $detail->id }}"
                                                        id="anonymous-{{ $detail->id }}" class="mr-2">
                                                    <label for="anonymous-{{ $detail->id }}" class="text-sm text-gray-600">Đánh giá ẩn
                                                        danh</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex items-center justify-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button type="button" wire:click="$set('showRatingModal', false)"
                                        class="border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium px-5 py-2.5">Hủy</button>
                                    <button type="button" wire:click="submitRatings"
                                        class="text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-lg text-sm px-5 py-2.5">Gửi
                                        đánh giá</button>
                                </div>
                            </div>
                        </div>
                    @endif


                    @php
                        $reasons = ['Tôi đặt nhầm', 'Thời gian giao hàng quá lâu', 'Muốn thay đổi sản phẩm', 'Tìm được giá tốt hơn', 'Lý do khác'];
                    @endphp

                    @if ($showCancelModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-white/200 bg-opacity-50">
                            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                                <h2 class="text-lg font-semibold mb-4">Chọn lý do hủy đơn</h2>

                                @error('reason')
                                    <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                                @enderror

                                <div x-data="{ reason: @entangle('selectedReason') }" class="space-y-2">
                                    @foreach (['Tôi đặt nhầm', 'Thời gian giao hàng quá lâu', 'Muốn thay đổi sản phẩm', 'Tìm được giá tốt hơn', 'Lý do khác'] as $reason)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="radio" name="cancel_reason" value="{{ $reason }}" x-model="reason"
                                                class="text-blue-600">
                                            <span>{{ $reason }}</span>
                                        </label>
                                    @endforeach

                                    <template x-if="reason === 'Lý do khác'">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mt-2">Nhập lý do của bạn:</label>
                                            <input type="text" wire:model.live="customReason" placeholder="Nhập lý do..." readonly
                                                onfocus="this.removeAttribute('readonly')"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                        </div>
                                    </template>
                                </div>



                                <div class="mt-4 flex justify-end gap-2">
                                    <button wire:click="$set('showCancelModal', false)"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm">
                                        Đóng
                                    </button>

                                    <button wire:click="cancelOrder"
                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                        Xác nhận hủy
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif





                @endforeach
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160.001" viewBox="0 0 160 160.001"
                        class="mdl-js">
                        <g transform="translate(-114.83 -59.67)">
                            <path class="a"
                                d="M274.83,139.664A80,80,0,1,1,194.835,59.67,79.751,79.751,0,0,1,274.83,139.664Z"
                                transform="translate(0 0)" />
                            <g transform="translate(115.028 134.896)">
                                <path class="b"
                                    d="M357.482,165.461a90.512,90.512,0,0,1-1.813,14.866c-.341,1.638-.715,3.253-1.152,4.845a91.87,91.87,0,0,1-20.9,28.85q-1.888,1.752-3.872,3.367a82.015,82.015,0,0,1-46.3,18.748c-1.941.149-3.9.218-5.887.218q-2.016,0-4-.1a81.857,81.857,0,0,1-48.144-18.828q-2.016-1.632-3.914-3.4a92.282,92.282,0,0,1-20.776-28.529c-.213-.779-.416-1.58-.608-2.371a90.62,90.62,0,0,1-2.432-17.511,37.132,37.132,0,0,1,13.865-2.84A22.814,22.814,0,0,1,221.5,164.8a18.291,18.291,0,0,1,3.978,2.543c8.778,7.169,10.346,20.352,23.891,20.352,18.633,0,18.185-24.921,44.624-24.921s16.979,24.921,31.591,24.921a12.037,12.037,0,0,0,4.16-.733,15.15,15.15,0,0,0,3.872-2.1C340.321,180.018,345.771,170.2,357.482,165.461Z"
                                    transform="translate(-197.68 -162.77)" />
                                <path class="c"
                                    d="M355.4,178.26c-.341,1.638-.714,3.253-1.151,4.845a86.657,86.657,0,0,1-24.758,41.585,75.751,75.751,0,0,1-104.253,0,86.672,86.672,0,0,1-24.672-41.264c-.213-.779-.416-1.581-.607-2.371A36.146,36.146,0,0,1,215.989,177a21.113,21.113,0,0,1,9.251,1.89c11.67,5.52,11.627,21.3,25.919,21.3,17.319,0,16.9-23.192,41.49-23.192s15.795,23.192,29.383,23.192a12.4,12.4,0,0,0,7.46-2.634C336.4,192.576,341.877,181.9,355.4,178.26Z"
                                    transform="translate(-197.463 -160.702)" />
                            </g>
                            <path class="d" d="M227.471,139.5v61.822a80.051,80.051,0,0,1-76.141-1.262V139.5Z"
                                transform="translate(4.268 9.334)" />
                            <path class="e" d="M136.17,151.83v34.022a79.913,79.913,0,0,0,109.638,2.535V151.83Z"
                                transform="translate(2.495 10.776)" />
                            <path class="f" d="M153.107,139.5l-16.94,13.775h16.94Z" transform="translate(2.495 9.334)" />
                            <path class="f" d="M219.5,139.5l16.564,13.775H219.5Z" transform="translate(12.239 9.334)" />
                            <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(184.036 165.297)" />
                            <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(199.83 165.297)" />
                            <path class="h" d="M179.407,158.305s-2.246-14.065,8.04-14.065,7.754,14.065,7.754,14.065"
                                transform="translate(7.526 9.888)" />
                            <g transform="translate(157.557 111.866)">
                                <rect class="d" width="3.551" height="3.551"
                                    transform="translate(2.511 27.146) rotate(45)" />
                                <rect class="d" width="3.551" height="3.551"
                                    transform="translate(67.883 24.636) rotate(45)" />
                                <line class="i" x2="7.003" transform="translate(5.021 8.386)" />
                                <line class="i" x2="7.003" transform="translate(64.991 3.502)" />
                                <line class="i" y2="7.003" transform="translate(8.523 4.884)" />
                                <line class="i" y2="7.003" transform="translate(68.493)" />
                            </g>
                            <g transform="translate(175.971 80.445)"><text class="j" transform="translate(0 29)">
                                    <tspan x="0" y="0">Z</tspan>
                                </text><text class="k" transform="translate(21 48)">
                                    <tspan x="0" y="0">Z</tspan>
                                </text><text class="l" transform="translate(8 63)">
                                    <tspan x="0" y="0">Z</tspan>
                                </text></g>
                        </g>
                    </svg>
                    <p class="text-gray-500 mt-2">Không có đơn hàng nào</p>
                </div>
            @endif
            @php
                $current = $orders->currentPage();
                $last = $orders->lastPage();
            @endphp

            @if ($orders->lastPage() > 1)
                <div class="flex justify-center items-center mt-6">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{-- Previous --}}
                        @if ($orders->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </span>
                        @else
                            <button wire:click="previousPage" wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                        @endif

                        {{-- Page numbers --}}
                        @for ($i = 1; $i <= $orders->lastPage(); $i++)
                            @if ($i == $orders->currentPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-500 text-white text-sm font-medium">
                                    {{ $i }}
                                </span>
                            @elseif ($i === 1 || $i === $orders->lastPage() || ($i >= $orders->currentPage() - 1 && $i <= $orders->currentPage() + 1))
                                <button wire:click="gotoPage({{ $i }})"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ $i }}
                                </button>
                            @elseif ($i === $orders->currentPage() - 2 || $i === $orders->currentPage() + 2)
                                <span
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                    ...
                                </span>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($orders->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        @else
                            <span
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif






        </div>


    </div>

    <!-- Chờ thanh toán -->
    {{-- <div class="hidden" id="pending-payment" role="tabpanel" aria-labelledby="pending-payment-tab">
        <div class="flex flex-col items-center justify-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160.001" viewBox="0 0 160 160.001"
                class="mdl-js">
                <g transform="translate(-114.83 -59.67)">
                    <path class="a" d="M274.83,139.664A80,80,0,1,1,194.835,59.67,79.751,79.751,0,0,1,274.83,139.664Z"
                        transform="translate(0 0)" />
                    <g transform="translate(115.028 134.896)">
                        <path class="b"
                            d="M357.482,165.461a90.512,90.512,0,0,1-1.813,14.866c-.341,1.638-.715,3.253-1.152,4.845a91.87,91.87,0,0,1-20.9,28.85q-1.888,1.752-3.872,3.367a82.015,82.015,0,0,1-46.3,18.748c-1.941.149-3.9.218-5.887.218q-2.016,0-4-.1a81.857,81.857,0,0,1-48.144-18.828q-2.016-1.632-3.914-3.4a92.282,92.282,0,0,1-20.776-28.529c-.213-.779-.416-1.58-.608-2.371a90.62,90.62,0,0,1-2.432-17.511,37.132,37.132,0,0,1,13.865-2.84A22.814,22.814,0,0,1,221.5,164.8a18.291,18.291,0,0,1,3.978,2.543c8.778,7.169,10.346,20.352,23.891,20.352,18.633,0,18.185-24.921,44.624-24.921s16.979,24.921,31.591,24.921a12.037,12.037,0,0,0,4.16-.733,15.15,15.15,0,0,0,3.872-2.1C340.321,180.018,345.771,170.2,357.482,165.461Z"
                            transform="translate(-197.68 -162.77)" />
                        <path class="c"
                            d="M355.4,178.26c-.341,1.638-.714,3.253-1.151,4.845a86.657,86.657,0,0,1-24.758,41.585,75.751,75.751,0,0,1-104.253,0,86.672,86.672,0,0,1-24.672-41.264c-.213-.779-.416-1.581-.607-2.371A36.146,36.146,0,0,1,215.989,177a21.113,21.113,0,0,1,9.251,1.89c11.67,5.52,11.627,21.3,25.919,21.3,17.319,0,16.9-23.192,41.49-23.192s15.795,23.192,29.383,23.192a12.4,12.4,0,0,0,7.46-2.634C336.4,192.576,341.877,181.9,355.4,178.26Z"
                            transform="translate(-197.463 -160.702)" />
                    </g>
                    <path class="d" d="M227.471,139.5v61.822a80.051,80.051,0,0,1-76.141-1.262V139.5Z"
                        transform="translate(4.268 9.334)" />
                    <path class="e" d="M136.17,151.83v34.022a79.913,79.913,0,0,0,109.638,2.535V151.83Z"
                        transform="translate(2.495 10.776)" />
                    <path class="f" d="M153.107,139.5l-16.94,13.775h16.94Z" transform="translate(2.495 9.334)" />
                    <path class="f" d="M219.5,139.5l16.564,13.775H219.5Z" transform="translate(12.239 9.334)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(184.036 165.297)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(199.83 165.297)" />
                    <path class="h" d="M179.407,158.305s-2.246-14.065,8.04-14.065,7.754,14.065,7.754,14.065"
                        transform="translate(7.526 9.888)" />
                    <g transform="translate(157.557 111.866)">
                        <rect class="d" width="3.551" height="3.551" transform="translate(2.511 27.146) rotate(45)" />
                        <rect class="d" width="3.551" height="3.551" transform="translate(67.883 24.636) rotate(45)" />
                        <line class="i" x2="7.003" transform="translate(5.021 8.386)" />
                        <line class="i" x2="7.003" transform="translate(64.991 3.502)" />
                        <line class="i" y2="7.003" transform="translate(8.523 4.884)" />
                        <line class="i" y2="7.003" transform="translate(68.493)" />
                    </g>
                    <g transform="translate(175.971 80.445)"><text class="j" transform="translate(0 29)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="k" transform="translate(21 48)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="l" transform="translate(8 63)">
                            <tspan x="0" y="0">Z</tspan>
                        </text></g>
                </g>
            </svg>
            <p class="text-gray-500 mt-2">Không có đơn hàng nào</p>
        </div>
    </div> --}}

    <!-- Vận chuyển -->
    {{-- <div class="hidden" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
        <div class="flex flex-col items-center justify-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160.001" viewBox="0 0 160 160.001"
                class="mdl-js">
                <g transform="translate(-114.83 -59.67)">
                    <path class="a" d="M274.83,139.664A80,80,0,1,1,194.835,59.67,79.751,79.751,0,0,1,274.83,139.664Z"
                        transform="translate(0 0)" />
                    <g transform="translate(115.028 134.896)">
                        <path class="b"
                            d="M357.482,165.461a90.512,90.512,0,0,1-1.813,14.866c-.341,1.638-.715,3.253-1.152,4.845a91.87,91.87,0,0,1-20.9,28.85q-1.888,1.752-3.872,3.367a82.015,82.015,0,0,1-46.3,18.748c-1.941.149-3.9.218-5.887.218q-2.016,0-4-.1a81.857,81.857,0,0,1-48.144-18.828q-2.016-1.632-3.914-3.4a92.282,92.282,0,0,1-20.776-28.529c-.213-.779-.416-1.58-.608-2.371a90.62,90.62,0,0,1-2.432-17.511,37.132,37.132,0,0,1,13.865-2.84A22.814,22.814,0,0,1,221.5,164.8a18.291,18.291,0,0,1,3.978,2.543c8.778,7.169,10.346,20.352,23.891,20.352,18.633,0,18.185-24.921,44.624-24.921s16.979,24.921,31.591,24.921a12.037,12.037,0,0,0,4.16-.733,15.15,15.15,0,0,0,3.872-2.1C340.321,180.018,345.771,170.2,357.482,165.461Z"
                            transform="translate(-197.68 -162.77)" />
                        <path class="c"
                            d="M355.4,178.26c-.341,1.638-.714,3.253-1.151,4.845a86.657,86.657,0,0,1-24.758,41.585,75.751,75.751,0,0,1-104.253,0,86.672,86.672,0,0,1-24.672-41.264c-.213-.779-.416-1.581-.607-2.371A36.146,36.146,0,0,1,215.989,177a21.113,21.113,0,0,1,9.251,1.89c11.67,5.52,11.627,21.3,25.919,21.3,17.319,0,16.9-23.192,41.49-23.192s15.795,23.192,29.383,23.192a12.4,12.4,0,0,0,7.46-2.634C336.4,192.576,341.877,181.9,355.4,178.26Z"
                            transform="translate(-197.463 -160.702)" />
                    </g>
                    <path class="d" d="M227.471,139.5v61.822a80.051,80.051,0,0,1-76.141-1.262V139.5Z"
                        transform="translate(4.268 9.334)" />
                    <path class="e" d="M136.17,151.83v34.022a79.913,79.913,0,0,0,109.638,2.535V151.83Z"
                        transform="translate(2.495 10.776)" />
                    <path class="f" d="M153.107,139.5l-16.94,13.775h16.94Z" transform="translate(2.495 9.334)" />
                    <path class="f" d="M219.5,139.5l16.564,13.775H219.5Z" transform="translate(12.239 9.334)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(184.036 165.297)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(199.83 165.297)" />
                    <path class="h" d="M179.407,158.305s-2.246-14.065,8.04-14.065,7.754,14.065,7.754,14.065"
                        transform="translate(7.526 9.888)" />
                    <g transform="translate(157.557 111.866)">
                        <rect class="d" width="3.551" height="3.551" transform="translate(2.511 27.146) rotate(45)" />
                        <rect class="d" width="3.551" height="3.551" transform="translate(67.883 24.636) rotate(45)" />
                        <line class="i" x2="7.003" transform="translate(5.021 8.386)" />
                        <line class="i" x2="7.003" transform="translate(64.991 3.502)" />
                        <line class="i" y2="7.003" transform="translate(8.523 4.884)" />
                        <line class="i" y2="7.003" transform="translate(68.493)" />
                    </g>
                    <g transform="translate(175.971 80.445)"><text class="j" transform="translate(0 29)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="k" transform="translate(21 48)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="l" transform="translate(8 63)">
                            <tspan x="0" y="0">Z</tspan>
                        </text></g>
                </g>
            </svg>
            <p class="text-gray-500 mt-2">Không có đơn hàng nào</p>
        </div>
    </div> --}}

    <!-- Chờ giao hàng -->
    {{-- <div class="hidden" id="waiting-delivery" role="tabpanel" aria-labelledby="waiting-delivery-tab">
        <div class="flex flex-col items-center justify-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160.001" viewBox="0 0 160 160.001"
                class="mdl-js">
                <g transform="translate(-114.83 -59.67)">
                    <path class="a" d="M274.83,139.664A80,80,0,1,1,194.835,59.67,79.751,79.751,0,0,1,274.83,139.664Z"
                        transform="translate(0 0)" />
                    <g transform="translate(115.028 134.896)">
                        <path class="b"
                            d="M357.482,165.461a90.512,90.512,0,0,1-1.813,14.866c-.341,1.638-.715,3.253-1.152,4.845a91.87,91.87,0,0,1-20.9,28.85q-1.888,1.752-3.872,3.367a82.015,82.015,0,0,1-46.3,18.748c-1.941.149-3.9.218-5.887.218q-2.016,0-4-.1a81.857,81.857,0,0,1-48.144-18.828q-2.016-1.632-3.914-3.4a92.282,92.282,0,0,1-20.776-28.529c-.213-.779-.416-1.58-.608-2.371a90.62,90.62,0,0,1-2.432-17.511,37.132,37.132,0,0,1,13.865-2.84A22.814,22.814,0,0,1,221.5,164.8a18.291,18.291,0,0,1,3.978,2.543c8.778,7.169,10.346,20.352,23.891,20.352,18.633,0,18.185-24.921,44.624-24.921s16.979,24.921,31.591,24.921a12.037,12.037,0,0,0,4.16-.733,15.15,15.15,0,0,0,3.872-2.1C340.321,180.018,345.771,170.2,357.482,165.461Z"
                            transform="translate(-197.68 -162.77)" />
                        <path class="c"
                            d="M355.4,178.26c-.341,1.638-.714,3.253-1.151,4.845a86.657,86.657,0,0,1-24.758,41.585,75.751,75.751,0,0,1-104.253,0,86.672,86.672,0,0,1-24.672-41.264c-.213-.779-.416-1.581-.607-2.371A36.146,36.146,0,0,1,215.989,177a21.113,21.113,0,0,1,9.251,1.89c11.67,5.52,11.627,21.3,25.919,21.3,17.319,0,16.9-23.192,41.49-23.192s15.795,23.192,29.383,23.192a12.4,12.4,0,0,0,7.46-2.634C336.4,192.576,341.877,181.9,355.4,178.26Z"
                            transform="translate(-197.463 -160.702)" />
                    </g>
                    <path class="d" d="M227.471,139.5v61.822a80.051,80.051,0,0,1-76.141-1.262V139.5Z"
                        transform="translate(4.268 9.334)" />
                    <path class="e" d="M136.17,151.83v34.022a79.913,79.913,0,0,0,109.638,2.535V151.83Z"
                        transform="translate(2.495 10.776)" />
                    <path class="f" d="M153.107,139.5l-16.94,13.775h16.94Z" transform="translate(2.495 9.334)" />
                    <path class="f" d="M219.5,139.5l16.564,13.775H219.5Z" transform="translate(12.239 9.334)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(184.036 165.297)" />
                    <circle class="g" cx="2.897" cy="2.897" r="2.897" transform="translate(199.83 165.297)" />
                    <path class="h" d="M179.407,158.305s-2.246-14.065,8.04-14.065,7.754,14.065,7.754,14.065"
                        transform="translate(7.526 9.888)" />
                    <g transform="translate(157.557 111.866)">
                        <rect class="d" width="3.551" height="3.551" transform="translate(2.511 27.146) rotate(45)" />
                        <rect class="d" width="3.551" height="3.551" transform="translate(67.883 24.636) rotate(45)" />
                        <line class="i" x2="7.003" transform="translate(5.021 8.386)" />
                        <line class="i" x2="7.003" transform="translate(64.991 3.502)" />
                        <line class="i" y2="7.003" transform="translate(8.523 4.884)" />
                        <line class="i" y2="7.003" transform="translate(68.493)" />
                    </g>
                    <g transform="translate(175.971 80.445)"><text class="j" transform="translate(0 29)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="k" transform="translate(21 48)">
                            <tspan x="0" y="0">Z</tspan>
                        </text><text class="l" transform="translate(8 63)">
                            <tspan x="0" y="0">Z</tspan>
                        </text></g>
                </g>
            </svg>
            <p class="text-gray-500 mt-2">Không có đơn hàng nào</p>
        </div>
    </div> --}}

    <!-- Hoàn thành -->
    {{-- <div class="hidden" id="completed" role="tabpanel" aria-labelledby="completed-tab">
        <div class="block" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="border border-gray-200 rounded-lg mx-4 mb-4">
                <div class="border-b border-gray-200 p-3 md:p-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <button class="bg-blue-100 text-red-500 px-2 py-1 rounded text-xs mr-3">Yêu
                            thích</button>
                        <span class="font-medium">Sách Giải</span>
                    </div>
                </div>

                <div class="p-3 md:p-4 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="w-14 h-14 md:w-16 md:h-16 mr-3 flex-shrink-0">
                            <img src="https://cdn1.fahasa.com/media/catalog/product/9/7/9786326020120.jpg"
                                alt="Sản phẩm" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium mb-1 text-sm md:text-base line-clamp-2">Tuyển Sinh 10
                                & Các Đề Toán Thực Tế (Theo Chương Trình Giáo Dục Phổ Thông 2018)</p>
                            <p class="text-gray-500 text-xs md:text-sm mb-1">Phân loại hàng: 1 đôi dài
                                đen</p>
                            <p class="text-gray-500 text-xs md:text-sm">x2</p>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <div class="flex flex-col justify-end items-end gap-1">
                                <span class="text-gray-500 text-xs">Combo Khuyến Mãi</span>
                                <span class="text-gray-500 text-xs">Mua 2, Tiết kiệm ₫2.000</span>
                            </div>
                            <div class="text-red-500 font-medium text-sm md:text-base">₫18.000</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 md:p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="flex items-center">
                        <svg class="text-green-500 w-4 h-4 md:w-5 md:h-5 mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-green-500 text-sm">Giao hàng thành công</span>
                        <span class="text-red-500 font-medium ml-2 text-sm">HOÀN THÀNH</span>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm text-right">Thành tiền: <span
                                class="text-red-500 text-base md:text-lg font-medium">₫26.000</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 md:p-4 border-t border-gray-200 flex flex-wrap gap-2 justify-end">
                    <div class="text-xs md:text-sm text-gray-500">Đánh giá sản phẩm trước 20-05-2025
                    </div>
                    <div class="text-xs md:text-sm text-red-500">Đánh giá ngay và nhận 200 Xu</div>
                </div>

                <div class="p-3 md:p-4 border-t border-gray-200 flex flex-wrap gap-2 justify-end">
                    <button
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Đánh
                        Giá</button>
                    <button
                        class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Liên
                        Hệ Người Bán</button>
                    <button class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Mua
                        Lại</button>
                    @livewire('component.export-order-pdf')
                </div>
            </div>

        </div>
    </div> --}}

    <!-- Đã hủy -->
    {{-- <div class="hidden" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
        <div class="block" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="border border-gray-200 rounded-lg mx-4 mb-4">
                <div class="border-b border-gray-200 p-3 md:p-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <button class="bg-blue-100 text-red-500 px-2 py-1 rounded text-xs mr-3">Yêu
                            thích</button>
                        <span class="font-medium">Sách Giải</span>
                    </div>
                </div>

                <div class="p-3 md:p-4 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="w-14 h-14 md:w-16 md:h-16 mr-3 flex-shrink-0">
                            <img src="https://cdn1.fahasa.com/media/catalog/product/9/7/9786326020120.jpg"
                                alt="Sản phẩm" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium mb-1 text-sm md:text-base line-clamp-2">Tuyển Sinh 10
                                & Các Đề Toán Thực Tế (Theo Chương Trình Giáo Dục Phổ Thông 2018)</p>
                            <p class="text-gray-500 text-xs md:text-sm mb-1">Phân loại hàng: 1 đôi dài
                                đen</p>
                            <p class="text-gray-500 text-xs md:text-sm">x2</p>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <div class="flex flex-col justify-end items-end gap-1">
                                <span class="text-gray-500 text-xs">Combo Khuyến Mãi</span>
                                <span class="text-gray-500 text-xs">Mua 2, Tiết kiệm ₫2.000</span>
                            </div>
                            <div class="text-red-500 font-medium text-sm md:text-base">₫18.000</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 md:p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div>
                        <div class="text-gray-600 text-sm text-right">Thành tiền: <span
                                class="text-red-500 text-base md:text-lg font-medium">₫26.000</span>
                        </div>
                    </div>
                </div>
                <div class="p-3 md:p-4 border-t border-gray-200 flex flex-wrap gap-2 justify-end">
                    <button
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Mua
                        Lại</button>
                    <button class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Xem
                        Chi Tiết Hủy Đơn</button>
                    <button
                        class="border border-gray-300 text-gray-700 px-4 md:px-6 py-1.5 md:py-2 rounded text-sm">Liên
                        Hệ Người Bán</button>
                </div>
            </div>

        </div>
    </div> --}}

    <!-- Trả hàng/Hoàn tiền -->
    {{-- <div class="hidden" id="refund" role="tabpanel" aria-labelledby="refund-tab">
        <div class="block" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="border border-gray-200 rounded-lg mx-4 mb-4">
                <div class="border-b border-gray-200 p-3 md:p-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <button class="bg-blue-100 text-red-500 px-2 py-1 rounded text-xs mr-3">Yêu
                            thích</button>
                        <span class="font-medium">Sách Giải</span>
                    </div>
                </div>

                <div class="p-3 md:p-4 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="w-14 h-14 md:w-16 md:h-16 mr-3 flex-shrink-0">
                            <img src="https://cdn1.fahasa.com/media/catalog/product/9/7/9786326020120.jpg"
                                alt="Sản phẩm" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium mb-1 text-sm md:text-base line-clamp-2">Tuyển Sinh 10 &
                                Các Đề Toán Thực Tế (Theo Chương Trình Giáo Dục Phổ Thông 2018)</p>
                            <p class="text-gray-500 text-xs md:text-sm mb-1">Phân loại hàng: 1 đôi dài đen
                            </p>
                            <p class="text-gray-500 text-xs md:text-sm">x2</p>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <div class="flex flex-col justify-end items-end gap-1">
                                <span class="text-gray-500 text-xs">Combo Khuyến Mãi</span>
                                <span class="text-gray-500 text-xs">Mua 2, Tiết kiệm ₫2.000</span>
                            </div>
                            <div class="text-red-500 font-medium text-sm md:text-base">₫18.000</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 md:p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="flex items-center">
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm text-right">Số tiền hoàn lại: <span
                                class="text-red-500 text-base md:text-lg font-medium">₫26.000</span></div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
</div>