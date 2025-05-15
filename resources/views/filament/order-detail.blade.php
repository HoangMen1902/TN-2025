<div class="space-y-8 p-6 bg-gray-900 text-white rounded-2xl shadow-lg border border-gray-700">
    <div class="text-xl font-bold border-b border-gray-700 pb-3 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-4-4H5a4 4 0 00-4 4v10a4 4 0 004 4h4" />
        </svg>
        Thông tin đơn hàng
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div><span class="font-medium text-gray-300">Mã đơn:</span> #{{ $order->id }}</div>
        <div><span class="font-medium text-gray-300">Khách hàng:</span> {{ $order->customer_name }}</div>
        <div><span class="font-medium text-gray-300">Số điện thoại:</span> {{ $order->phone }}</div>
        <div><span class="font-medium text-gray-300">Địa chỉ:</span> {{ $order->address }}</div>
        <div><span class="font-medium text-gray-300">Ngày đặt:</span> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Chưa có' }}</div>
        <div>
            <span class="font-medium text-gray-300">Trạng thái:</span> 
            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium 
                {{ $order->orders_status == 'Đã giao' ? 'bg-green-700 text-green-100' : 'bg-yellow-700 text-yellow-100' }}">
                {{ $order->orders_status }}
            </span>
        </div>
        <div>
            <span class="font-medium text-gray-300">Duyệt:</span>
            <span class="{{ $order->is_approved ? 'text-green-400 font-semibold' : 'text-red-400 font-semibold' }}">
                {{ $order->is_approved ? 'Đã duyệt' : 'Chưa duyệt' }}
            </span>
        </div>
        <div>
            <span class="font-medium text-gray-300">Tổng tiền:</span>
            <span class="text-indigo-400 font-semibold">
                {{ number_format($order->calculated_total_price, 0, ',', '.') }} đ
            </span>
        </div>
    </div>

    <div>
        <div class="text-lg font-semibold mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h18M3 12h18M3 17h18" />
            </svg>
            Sản phẩm trong đơn
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-700">
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead class="bg-gray-800 text-gray-300 font-medium text-left">
                    <tr>
                        <th class="px-4 py-2">Ảnh</th>
                        <th class="px-4 py-2">Tên sản phẩm</th>
                        <th class="px-4 py-2">SKU</th>
                        <th class="px-4 py-2">Thuộc tính</th>
                        <th class="px-4 py-2">Giá</th>
                        <th class="px-4 py-2">Số lượng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach ($order->orderDetails as $item)
                        <tr class="hover:bg-gray-800">
                            <td class="px-4 py-2">
                                @php
                                    $imageUrl = $item->sku && $item->sku->product && $item->sku->product->thumbnail 
                                                ? asset($item->sku->product->thumbnail) 
                                                : 'https://via.placeholder.com/48x48?text=No+Image';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Ảnh sản phẩm" class="w-12 h-12 object-cover rounded-md border border-gray-600" />
                            </td>
                            <td class="px-4 py-2 font-medium">
                                {{ $item->sku && $item->sku->product ? $item->sku->product->name : '---' }}
                            </td>
                            <td class="px-4 py-2">{{ $item->sku->sku ?? '---' }}</td>
                            <td class="px-4 py-2 space-x-1">
                                @if ($item->sku && $item->sku->skuValues)
                                    @foreach ($item->sku->skuValues as $value)
                                        <span class="inline-block bg-gray-700 text-gray-200 text-xs px-2 py-0.5 rounded-full">
                                            {{ $value->optionValue->option->name ?? '---' }}:
                                            {{ $value->optionValue->name ?? '---' }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">---</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
