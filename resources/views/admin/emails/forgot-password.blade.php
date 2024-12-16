<!DOCTYPE html>
<html>
<head>
    <title>Đặt lại mật khẩu</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xin chào!</h2>
        
        <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
        
        <p>Vui lòng click vào nút bên dưới để đặt lại mật khẩu:</p>
        
        <a href="{{ route('admin.resetPassword', $token) }}" class="button">Đặt lại mật khẩu</a>
        
        <p>Link đặt lại mật khẩu này sẽ hết hạn sau 60 phút.</p>
        
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này.</p>
        
        <div class="footer">
            <p>Nếu bạn gặp sự cố khi click vào nút "Đặt lại mật khẩu", hãy copy và dán URL sau vào trình duyệt web của bạn: {{ route('admin.resetPassword', $token) }}</p>
            
            <p>Đây là email tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html> 