<div class="space-y-8">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 space-y-4">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Đơn hàng #{{ $order->id }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Đặt lúc: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Chưa có' }}
                </p>
            </div>

            <div class="text-right flex-shrink-0">
                <div class="text-sm text-gray-500 dark:text-gray-400">Tổng tiền</div>
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ number_format($order->calculated_total_price, 0, ',', '.') }} đ
                </p>
            </div>

        </div>

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Trạng thái đơn hàng</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full font-semibold border
                    {{ $order->orders_status == 'Đã giao' 
                        ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/50 dark:text-green-300 dark:border-green-700' 
                        : 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/50 dark:text-yellow-300 dark:border-yellow-700' }}">
                    {{ $order->orders_status ?? 'Chờ xử lý' }}
                </span>
            </div>
            <div>
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Trạng thái duyệt</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full font-semibold border
                    {{ $order->is_approved 
                        ? 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700' 
                        : 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/50 dark:text-red-300 dark:border-red-700' }}">
                    {{ $order->is_approved ? 'Đã duyệt' : 'Chưa duyệt' }}
                </span>
            </div>

            <div>
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Thanh toán
                </p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $order->payment_method ?? 'Chưa cập nhật' }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h8a1 1 0 001-1z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18h1a1 1 0 001-1v-3.337a1 1 0 00-.447-.826L14.99 9.999" />
                    </svg>
                    Vận chuyển
                </p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $order->shipping_method ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Sản phẩm trong đơn ({{ $order->orderDetails->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600 dark:text-gray-300 font-medium text-left">
                            <tr>
                                <th class="px-6 py-3">Sản phẩm</th>
                                <th class="px-6 py-3 text-right">Giá</th>
                                <th class="px-6 py-3 text-right">Số lượng</th>
                                <th class="px-6 py-3 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach ($order->orderDetails as $item)
                            <tr >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $item->sku && $item->sku->product && $item->sku->product->thumbnail ? asset($item->sku->product->thumbnail) : 'https://via.placeholder.com/64x64?text=N/A' }}"
                                            alt="Ảnh sản phẩm" class="w-16 h-16 object-cover rounded-lg border border-gray-300 dark:border-gray-600" />
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-white">
                                                {{ $item->sku && $item->sku->product ? $item->sku->product->name : 'Sản phẩm không tồn tại' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">SKU: {{ $item->sku->sku ?? '---' }}</p>
                                            <div class="mt-1 space-x-1">
                                                @if ($item->sku && $item->sku->skuValues)
                                                @foreach ($item->sku->skuValues as $value)
                                                <span class="inline-block bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs px-2 py-0.5 rounded-full">
                                                    {{ $value->optionValue->option->name ?? 'N/A' }}:
                                                    {{ $value->optionValue->name ?? 'N/A' }}
                                                </span>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">x {{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap font-semibold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Thông tin khách hàng
                    </h3>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div class="flex">
                        <dt class="w-1/3 font-medium text-gray-500 dark:text-gray-400">Tên khách hàng: </dt>
                        <dd class="w-2/3 text-gray-800 dark:text-white font-semibold">{{ $order->customer_name }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 font-medium text-gray-500 dark:text-gray-400">Điện thoại: </dt>
                        <dd class="w-2/3 text-gray-800 dark:text-white">{{ $order->phone }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 font-medium text-gray-500 dark:text-gray-400">Địa chỉ: </dt>
                        <dd class="w-2/3 text-gray-800 dark:text-white">{{ $order->address }}</dd>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Hành động
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <button class="w-full text-center px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition">
                        Cập nhật trạng thái
                    </button>
                    <button class="w-full text-center px-4 py-2 rounded-lg font-semibold text-gray-800 dark:text-white bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition">
                        In hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>