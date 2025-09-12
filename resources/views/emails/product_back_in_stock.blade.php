<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm đã có hàng</title>
</head>
<body>
    <h2>🎉 Sản phẩm "{{ $product->name }}" đã có hàng!</h2>
    <p>Chào bạn,</p>
    <p>Sản phẩm bạn đặt trước hiện đã có mặt trên cửa hàng. Hãy truy cập ngay để đặt mua!</p>

    @if ($product->thumbnail)
        <p>
            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" width="300">
        </p>
    @endif

    <p><strong>Beebook xin cảm ơn!</strong></p>
</body>
</html>
