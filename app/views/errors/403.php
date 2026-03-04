<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập</title>
    <link rel="stylesheet" href="/views/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/views/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #f5576c;
            margin: 0;
            line-height: 1;
        }
        .error-icon {
            font-size: 80px;
            color: #f5576c;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin: 20px 0;
        }
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-home:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa fa-ban"></i>
        </div>
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Không có quyền truy cập</h2>
        <p class="error-message">
            Xin lỗi! Bạn không có quyền truy cập vào trang này.<br>
            Vui lòng đăng nhập với tài khoản Admin để tiếp tục.
        </p>
        <a href="/" class="btn-home">
            <i class="fa fa-home"></i> Về Trang Chủ
        </a>
    </div>
</body>
</html>

