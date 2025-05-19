<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hóa đơn #{{ $order['id'] }}</title>
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
       font-family: 'DejaVu Sans', Arial, sans-serif;
      line-height: 1.5;
      -webkit-text-size-adjust: 100%;
      tab-size: 4;
      margin: 0;
      padding: 0;
      font-feature-settings: normal;
      font-variation-settings: normal;
    }

    body {
      margin: 0;
      line-height: inherit;
      color: #404040;
      font-size: 14px;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    img {
      display: block;
      vertical-align: middle;
      max-width: 100%;
      height: auto;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
    }

    .h-12 {
      height: 3rem;
    }

    .w-full {
      width: 100%;
    }

    .w-1\/2 {
      width: 50%;
    }

    .border-collapse {
      border-collapse: collapse;
    }

    .border-spacing-0 {
      border-spacing: 0;
    }

    .border-main {
      border-color: #5c6ac4;
    }

    .bg-main {
      background-color: #5c6ac4;
    }

    .bg-slate-100 {
      background-color: #f1f5f9;
    }

    .text-main {
      color: #5c6ac4;
    }

    .text-neutral-600 {
      color: #525252;
    }

    .text-neutral-700 {
      color: #404040;
    }

    .text-slate-400 {
      color: #94a3b8;
    }

    .text-slate-300 {
      color: #cbd5e1;
    }

    .text-white {
      color: #fff;
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .text-xs {
      font-size: 0.75rem;
      line-height: 1rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .italic {
      font-style: italic;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .whitespace-nowrap {
      white-space: nowrap;
    }

    .align-top {
      vertical-align: top;
    }

    .p-3 {
      padding: 0.75rem;
    }

    .px-14 {
      padding-left: 3.5rem;
      padding-right: 3.5rem;
    }

    .px-2 {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .py-10 {
      padding-top: 2.5rem;
      padding-bottom: 2.5rem;
    }

    .py-6 {
      padding-top: 1.5rem;
      padding-bottom: 1.5rem;
    }

    .py-4 {
      padding-top: 1rem;
      padding-bottom: 1rem;
    }

    .py-3 {
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
    }

    .pb-3 {
      padding-bottom: 0.75rem;
    }

    .pl-2 {
      padding-left: 0.5rem;
    }

    .pl-3 {
      padding-left: 0.75rem;
    }

    .pl-4 {
      padding-left: 1rem;
    }

    .pr-3 {
      padding-right: 0.75rem;
    }

    .pr-4 {
      padding-right: 1rem;
    }

    .border-b {
      border-bottom: 1px solid #5c6ac4;
    }

    .border-b-2 {
      border-bottom: 2px solid #5c6ac4;
    }

    .border-r {
      border-right: 1px solid #5c6ac4;
    }

    footer.fixed {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #f1f5f9;
      color: #525252;
      text-align: center;
      font-size: 0.75rem;
      padding: 0.75rem 0;
    }

    @page {
      margin: 0;
    }

    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
</head>

<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <h1>BeeBook</h1>
              </td>
              <td class="align-top">
                <table class="text-sm border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td class="border-r pr-4">
                        <p class="whitespace-nowrap text-slate-400 text-right">Ngày lập</p>
                        <p class="whitespace-nowrap font-bold text-main text-right">{{ $order['created_at']->format('d/m/Y') }}</p>
                      </td>
                      <td class="pl-4">
                        <p class="whitespace-nowrap text-slate-400 text-right">Mã đơn</p>
                        <p class="whitespace-nowrap font-bold text-main text-right">{{ $order['id'] }}</p>
                      </td>
                      <td class="pl-4">
                        <p class="whitespace-nowrap text-slate-400 text-right">Hình thức thanh toán</p>
                        <p class="whitespace-nowrap font-bold text-main text-right">{{ $order['payment_method'] ?? 'Tiền mặt' }}</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top text-neutral-600">
                <p class="font-bold">BeeBook Company</p>
                <p>Hotline: 18001234</p>
                <p>Mã số thuế: 987654321</p>
                <p>123 Đường 3 Tháng 2</p>
                <p>Ninh Kiều, Cần Thơ</p>
                <p>Việt Nam</p>
              </td>
              <td class="w-1/2 align-top text-right text-neutral-600">
                <p class="font-bold">{{ $order['customer_name'] }}</p>
                <p>Số điện thoại: 0123456789</p>
                <p>VAT: 123456789</p>
                <p>{{ $order['customer_address'] ?? 'Địa chỉ khách hàng' }}</p>
                <p>Ninh Kiều, TP. Cần Thơ</p>
                <p>Việt Nam</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <th class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-left">STT</th>
              <th class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-left">Tên sản phẩm</th>
              <th class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Đơn giá</th>
              <th class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Số lượng</th>
              <th class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Tạm tính</th>
              <th class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Giảm giá</th>
              <th class="border-b-2 border-main pb-3 pl-2 pr-3 text-right font-bold text-main">Tổng tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order['items'] as $index => $item)
            <tr>
              <td class="border-b border-main p-3 font-bold text-main">{{ $index + 1 }}</td>
              <td class="border-b border-main p-3">{{ $item['product_name'] }}</td>
              <td class="border-b border-main p-3 text-right whitespace-nowrap">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
              <td class="border-b border-main p-3 text-center whitespace-nowrap">{{ $item['quantity'] }}</td>
              <td class="border-b border-main p-3 text-center whitespace-nowrap">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</td>
              <td class="border-b border-main p-3 text-right whitespace-nowrap">0đ</td>
              <td class="border-b border-main p-3 text-right whitespace-nowrap">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-14 py-6 text-sm text-neutral-600 text-right">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="font-bold text-main pr-4" style="width: 60%; ">Tổng thanh toán:</td>
              <td class="font-bold pr-4 text-right whitespace-nowrap" style="width: 40%;">{{ number_format($order['total'], 0, ',', '.') }} đ</td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer class="fixed">
        © 2025 BeeBook Company
      </footer>
    </div>
  </div>
</body>

</html>
