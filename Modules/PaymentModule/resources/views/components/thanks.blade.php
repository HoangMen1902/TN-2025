<x-layouts.layout>
  <div class="max-w-4xl mx-auto my-12 px-4 sm:px-6 lg:px-8 py-6 bg-white shadow-xl rounded-2xl">
    <div class="text-center">
      <h1 class="text-3xl font-bold text-blue-600 mb-2">Cảm ơn bạn đã đặt hàng!</h1>
      <p class="text-gray-600">Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý.</p>
    </div>
    <!-- Thông tin đơn hàng -->
    <div class="mt-8">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Chi tiết đơn hàng</h2>
      <div class="mb-4">
        <h4 class="">Mã tra cứu đơn hàng: <span class="font-bold">{{$payment->tracking_id ?? ''}}</span></h4>
        <h4 class="">Trạng thái: <span class="font-bold">{{$payment->order->orders_status ?? ''}}</span></h4>
      </div>
      <!-- Bảng sản phẩm -->
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm sm:text-base">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="text-left px-4 py-2">Tên sản phẩm</th>
              <th class="text-center px-4 py-2">Số lượng</th>
              <th class="text-center px-4 py-2">Loại</th>
              <th class="text-right px-4 py-2">Giá</th>
            </tr>
          </thead>
          <tbody class="text-gray-800">
            @foreach ($payment->order->orderDetails as $item)
          <tr class="border-t">
            <td class="px-4 py-2">{{$item->sku ? $item->sku->product->name : $item->combo->combo_name}}</td>
            <td class="text-center px-4 py-2">{{$item->quantity}}</td>
            <td class="text-center px-4 py-2">
            @if ($item->sku)
            @foreach ($item->sku->optionValues as $index => $value)
          {{ $value->value_name }}{{ !$loop->last ? ', ' : '' }}
          @endforeach
        @endif
            </td>

            <td class="text-right px-4 py-2">{{number_format($item->price * $item->quantity, 0, '.', '.')}}₫</td>
          </tr>
      @endforeach
          </tbody>
        </table>
      </div>

      <!-- Thông tin giao hàng & thanh toán -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div>
          <h3 class="font-semibold text-gray-700 mb-2">Địa chỉ giao hàng</h3>
          <p class="text-gray-600 text-sm sm:text-base">
            {{$payment->order->customer_name ?? ''}} - {{$payment->order->phone ?? ''}}<br>{{$payment->order->address ?? ''}}
          </p>
        </div>
        <div>
          <h3 class="font-semibold text-gray-700 mb-2">Phương thức thanh toán</h3>
          <p class="text-gray-600 text-sm sm:text-base">
            @if ($payment->payment_method === 'cod')
             Thanh toán tiền mặt - COD
            @elseif($payment->payment_method === 'bank_transfer')
              Thanh toán chuyển khoản
            @elseif($payment->payment_method === 'international')
            Thanh toán quốc tế
            @endif
          </p>
        </div>
      </div>

      <!-- Tổng chi phí -->
      <div class="mt-8 border-t pt-4 space-y-2 text-sm sm:text-base">
<div class="flex justify-between text-gray-700">
  <span>Tạm tính:</span>
  <span>
    {{
      number_format(
        $payment->order->orderDetails->sum(function ($detail) {
          return $detail->price * $detail->quantity;
        }),
        0, '.', '.'
      )
    }}₫
  </span>
</div>
        <div class="flex justify-between text-gray-700">
          <span>Phí vận chuyển:</span>
          <span>{{number_format($payment->order->shipment_price, 0,'.','.')}}₫</span>
        </div>
        <div class="flex justify-between text-lg font-bold text-gray-800 pt-2">
          <span>Tổng thanh toán:</span>
          <span>{{number_format($payment->order->total_price, 0,'.','.')}}₫</span>
        </div>
      </div>
    </div>

    <!-- Nút quay lại trang chủ -->
    <div class="text-center mt-10">
      <a href="/"
        class="inline-block px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition text-sm sm:text-base">
        Quay lại trang chủ
      </a>
    </div>
  </div>

</x-layouts.layout>