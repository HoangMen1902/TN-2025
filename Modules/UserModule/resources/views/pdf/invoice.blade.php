<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hóa đơn #{{ $order['id'] }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.5;
        }

        body {
            margin: 0;
            font-size: 14px;
            color: #404040;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-main {
            color: #5c6ac4;
        }

        .bg-light {
            background-color: #f1f5f9;
        }

        .border-bottom {
            border-bottom: 1px solid #5c6ac4;
        }

        .border-bottom-2 {
            border-bottom: 2px solid #5c6ac4;
        }

        .p-4 {
            padding: 1rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .pb-3 {
            padding-bottom: 0.75rem;
        }

        .pt-3 {
            padding-top: 0.75rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 12px;
            text-align: center;
            padding: 0.5rem 0;
            background-color: #f1f5f9;
            color: #525252;
        }
    </style>
</head>

<body>
    <div class="p-4">
        <table class="pb-3">
            <tr>
                <td class="text-left">
                    <h2>BeeBook</h2>
                </td>
                <td class="text-right">
                    <table>
                        <tr>
                            <td class="text-right pr-2 whitespace-nowrap">
                                <small class="text-main">Ngày lập:</small><br>
                                <span class="font-bold">{{ $order['created_at']->format('d/m/Y') }}</span>
                            </td>
                            <td class="text-right pr-2 whitespace-nowrap">
                                <small class="text-main">Mã đơn:</small><br>
                                <span class="font-bold">#{{ $order['id'] }}</span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <small class="text-main">Thanh toán:</small><br>
                                <span class="font-bold">{{ $order['payment_method'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="bg-light p-2">
            <tr>
                <td class="text-left" style="width: 50%;">
                    <p class="font-bold">BeeBook Company</p>
                    <p>Hotline: 18001234</p>
                    <p>Mã số thuế: 987654321</p>
                    <p>123 Đường 3 Tháng 2, Ninh Kiều, Cần Thơ</p>
                </td>
                <td class="text-right" style="width: 50%;">
                    <p class="font-bold">Khách hàng: {{ $order['customer_name'] }}</p>
                    <p>Số điện thoại: {{ $order['customer_phone'] }}</p>
                    <p>Địa chỉ: {{ $order['customer_address'] }}</p>
                </td>
            </tr>
        </table>

        <table class="mt-4">
            <thead>
                <tr class="border-bottom-2 text-main font-bold">
                    <th class="text-left p-2">STT</th>
                    <th class="text-left p-2">Tên sản phẩm</th>
                    <th class="text-right p-2">Đơn giá</th>
                    <th class="text-center p-2">SL</th>
                    <th class="text-right p-2">Giảm giá</th>
                    <th class="text-right p-2">Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order['items'] as $index => $item)
                    <tr>
                        <td class="p-2">{{ $index + 1 }}</td>
                        <td class="p-2">{{ $item['product_name'] }}</td>
                        <td class="p-2 text-right">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                        <td class="p-2 text-center">{{ $item['quantity'] }}</td>
                        <td class="p-2 text-right">{{ number_format($item['discount'], 0, ',', '.') }} đ</td>
                        <td class="p-2 text-right">{{ number_format($item['total_price'], 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="mt-4 text-right">
            <tr>
                <td class="font-bold text-main text-right p-2">Tổng thanh toán:</td>
                <td class="font-bold text-right p-2 whitespace-nowrap">
                    {{ number_format($order['total'], 0, ',', '.') }} đ
                </td>
            </tr>
        </table>
    </div>

    <footer>
        © 2025 BeeBook Company
    </footer>
</body>

</html>