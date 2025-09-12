<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 40px auto;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e1e4e8;
            text-align: center;
        }
        h2 {
            color: #222;
            font-size: 22px;
            margin-bottom: 20px;
        }
        p {
            color: #444;
            font-size: 16px;
            margin: 10px 0;
        }
        .btn-link {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Khôi phục mật khẩu BeeBook</h2>
        <p>Xin chào,</p>
        <p>Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấn vào nút bên dưới để thực hiện:</p>
        <a href="{{ $resetLink }}" class="btn-link">Tại đây</a>
        <p style="margin-top: 30px;">Liên kết có hiệu lực trong vòng <strong>30 phút</strong>.</p>
        <p>Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này.</p>
        <div class="footer">© {{ date('Y') }} BeeBook. Bản quyền thuộc về chúng tôi.</div>
    </div>
</body>
</html>
