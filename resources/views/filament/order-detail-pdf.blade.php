{{-- resources/views/filament/order-detail-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->id }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 16px 0 8px 0;
        }
    </style>
</head>
@php
    $shippingStatusMap = [
        'chua_dang_don' => 'Chưa đăng đơn',
        'da_tao_don' => 'Đã tạo đơn',
        'dang_lay_hang' => 'Đang lấy hàng',
        'da_lay_hang' => 'Đã lấy hàng',
        'dang_van_chuyen' => 'Đang vận chuyển',
        'da_giao_thanh_cong' => 'Đã giao thành công',
        'giao_hang_that_bai' => 'Giao hàng thất bại',
        'cho_tra_lai' => 'Chờ trả lại',
        'da_tra_lai' => 'Đã trả lại',
        'co_su_co' => 'Có sự cố',
        'da_huy' => 'Đã hủy',
        'cho_duyet_hoan' => 'Chờ duyệt hoàn',
        'duyet_hoan' => 'Đã duyệt hoàn',
        'phat_thanh_cong_tieu_huy' => 'Phát thành công tiêu hủy',
    ];

    $paymentMethodMap = [
        'payos' => 'PayOS',
        'vnpay' => 'VNPay',
        'momo' => 'Momo',
        'cod' => 'Thanh toán khi nhận hàng (COD)',
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'international' => 'Quốc tế',
    ];
@endphp

<body>
    <div class="title">HÓA ĐƠN #{{ $order->id }}</div>
    <div>Ngày đặt: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Chưa có' }}</div>
    <div>Tổng tiền: <b>{{ number_format($order->calculated_total_price, 0, ',', '.') }} đ</b></div>
    <div>Trạng thái đơn hàng: <b>{{ $order->orders_status ?? 'Chờ xử lý' }}</b></div>
    <div>Phương thức thanh toán:
        <b>{{ $paymentMethodMap[$order->paymentDetail->payment_method ?? ''] ?? 'Chưa cập nhật' }}</b></div>
    <div>Trạng thái vận chuyển: <b>{{ $shippingStatusMap[$order->shipping_status ?? ''] ?? 'Chưa cập nhật' }}</b></div>

    <div class="section-title">Sản phẩm trong đơn ({{ $order->orderDetails->count() }})</div>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>SKU</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $item)
                <tr>
                    <td>
                        {{ $item->sku && $item->sku->product ? $item->sku->product->name : 'Sản phẩm không tồn tại' }}
                    </td>
                    <td>{{ $item->sku->sku ?? '---' }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                    <td>x {{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Thông tin khách hàng</div>
    <div>Tên khách hàng: <b>{{ $order->customer_name }}</b></div>
    <div>Điện thoại: <b>{{ $order->phone }}</b></div>
    <div>Địa chỉ: <b>{{ $order->address }}</b></div>
</body>

</html>