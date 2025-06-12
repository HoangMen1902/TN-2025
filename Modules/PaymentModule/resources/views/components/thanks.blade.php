<x-layouts.layout>
<div class="max-w-4xl mx-auto my-12 px-4 sm:px-6 lg:px-8 py-6 bg-white shadow-xl rounded-2xl">
  <div class="text-center">
    <h1 class="text-3xl font-bold text-blue-600 mb-2">Cảm ơn bạn đã đặt hàng!</h1>
    <p class="text-gray-600">Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý.</p>
  </div>
  <!-- Thông tin đơn hàng -->
  <div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Chi tiết đơn hàng</h2>

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
          <tr class="border-t">
            <td class="px-4 py-2">Truyện 1</td>
            <td class="text-center px-4 py-2">2</td>
            <td class="text-center px-4 py-2">Bìa Cứng</td>
            <td class="text-right px-4 py-2">200.000₫</td>
          </tr>
          <tr class="border-t">
            <td class="px-4 py-2">Sách 2</td>
            <td class="text-center px-4 py-2">1</td>
            <td class="text-center px-4 py-2">Bìa Mềm</td>
            <td class="text-right px-4 py-2">450.000₫</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Thông tin giao hàng & thanh toán -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <div>
        <h3 class="font-semibold text-gray-700 mb-2">Địa chỉ giao hàng</h3>
        <p class="text-gray-600 text-sm sm:text-base">
          Nguyễn Văn A<br>123 Đường ABC, Phường 1, Quận 3<br>TP.HCM
        </p>
      </div>
      <div>
        <h3 class="font-semibold text-gray-700 mb-2">Phương thức thanh toán</h3>
        <p class="text-gray-600 text-sm sm:text-base">Thanh toán khi nhận hàng (COD)</p>
      </div>
    </div>

    <!-- Tổng chi phí -->
    <div class="mt-8 border-t pt-4 space-y-2 text-sm sm:text-base">
      <div class="flex justify-between text-gray-700">
        <span>Tạm tính:</span>
        <span>650.000₫</span>
      </div>
      <div class="flex justify-between text-gray-700">
        <span>Phí vận chuyển:</span>
        <span>30.000₫</span>
      </div>
      <div class="flex justify-between text-lg font-bold text-gray-800 pt-2">
        <span>Tổng thanh toán:</span>
        <span>680.000₫</span>
      </div>
    </div>
  </div>

  <!-- Nút quay lại trang chủ -->
  <div class="text-center mt-10">
    <a href="/" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition text-sm sm:text-base">
      Quay lại trang chủ
    </a>
  </div>
</div>

</x-layouts.layout>