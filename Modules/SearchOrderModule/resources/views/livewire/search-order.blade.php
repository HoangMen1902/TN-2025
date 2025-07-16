<div>
    <!-- Tra cứu mã phiếu gửi -->
    <div class="max-w-md mx-auto mt-10 mb-20 text-left">
        <label class="block font-bold text-xl mb-2">Mã phiếu gửi</label>
        <p class="text-gray-600 mb-2">(Tra nhiều bill bằng cách thêm dấu phẩy giữa các bill)</p>
        <input type="text" wire:model="tracking_id" class="w-full border rounded-lg px-4 py-3 mb-4 text-gray-700" placeholder="VD : 12354,45677">
        <button
            wire:click="search"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg flex items-center gap-2">
            TRA CỨU
            <span class="ml-2">&rarr;</span>
        </button>
        @if ($error)
            <div class="mt-4 text-red-600">{{ $error }}</div>
        @endif
    </div>

    <!-- Thông tin đơn hàng & lịch sử: chỉ hiện khi có kết quả -->
    @if ($result)
        <div class="max-w-4xl mx-auto mb-10 mt-10 bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-3 gap-6 mb-6 border-b pb-4 border-gray-500">
                <div>
                    <div class="font-semibold text-gray-700 mb-2">THÔNG TIN ĐƠN HÀNG</div>
                    <div class="text-sm mb-1">Mã đơn hàng: <span class="font-bold">{{ $result['data']['order_code'] ?? '...' }}</span></div>
                    <div class="text-sm mb-1">Ngày lấy dự kiến: <span class="font-bold">{{ $result['data']['pick_date'] ?? '...' }}</span></div>
                    <div class="text-sm mb-1">Ngày giao dự kiến: <span class="font-bold">{{ $result['data']['deliver_date'] ?? '...' }}</span></div>
                    <div class="text-sm">Trạng thái hiện tại: <span class="bg-blue-700 text-white px-2 py-1 rounded text-xs">{{ $result['data']['status'] ?? '...' }}</span></div>
                </div>
                <div>
                    <div class="font-semibold text-gray-700 mb-2">NGƯỜI GỬI</div>
                    <div class="text-sm mb-1">Họ và tên: <span class="font-bold">{{ $result['data']['sender_name'] ?? '...' }}</span></div>
                    <div class="text-sm mb-1">Điện thoại: <span class="font-bold">{{ $result['data']['sender_phone'] ?? '...' }}</span></div>
                    <div class="text-sm">Địa chỉ: <span class="font-bold">{{ $result['data']['sender_address'] ?? '...' }}</span></div>
                </div>
                <div>
                    <div class="font-semibold text-gray-700 mb-2">NGƯỜI NHẬN</div>
                    <div class="text-sm mb-1">Họ và tên: <span class="font-bold">{{ $result['data']['receiver_name'] ?? '...' }}</span></div>
                    <div class="text-sm mb-1">Điện thoại: <span class="font-bold">{{ $result['data']['receiver_phone'] ?? '...' }}</span></div>
                    <div class="text-sm">Địa chỉ: <span class="font-bold">{{ $result['data']['receiver_address'] ?? '...' }}</span></div>
                </div>
            </div>

            <!-- Lịch sử đơn hàng -->
            <div class="bg-gray-50 rounded-lg p-4 mt-6 border-t border-gray-300">
                <div class="font-semibold text-gray-700 mb-2">Lịch sử đơn hàng</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-3 text-left">Thứ</th>
                            <th class="py-2 px-3 text-left">Chi tiết</th>
                            <th class="py-2 px-3 text-left">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result['data']['history'] ?? [] as $item)
                            <tr>
                                <td class="py-2 px-3 text-blue-700 font-semibold">{{ $item['status'] ?? '' }}</td>
                                <td class="py-2 px-3">{{ $item['location'] ?? '' }}</td>
                                <td class="py-2 px-3">{{ $item['time'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>