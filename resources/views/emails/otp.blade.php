<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mã OTP xác thực</title>
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
        .otp-box {
            display: inline-block;
            background-color: #f0f4ff;
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 20px 30px;
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 8px;
            margin: 30px 0;
        }
        p {
            color: #444;
            font-size: 16px;
            margin: 10px 0;
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
        <h2>Mã OTP xác thực thay đổi email</h2>
        <p>Xin chào <strong>{{ $name }}</strong>,</p>
        <p>Đây là mã OTP để xác thực thay đổi địa chỉ email của bạn:</p>
        <div class="otp-box">{{ $otp }}</div>
        <p>Mã có hiệu lực trong vòng <strong>10 phút</strong>.</p>
        <p>Nếu bạn không yêu cầu thay đổi email, vui lòng bỏ qua email này.</p>
        <div class="footer">© {{ date('Y') }} BeeBook. Bản quyền thuộc về chúng tôi.</div>
    </div>
</body>
</html>
