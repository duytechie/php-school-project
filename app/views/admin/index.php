<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CT275 Store</title>
    <link rel="stylesheet" href="/views/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/views/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
        }

        .admin-title {
            text-align: center;
            color: #667eea;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
        }

        .admin-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .admin-card i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .admin-card h3 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        .home-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
            text-decoration: none;
            text-align: center;
        }

        .home-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
            color: white;
            text-decoration: none;
        }

        .card-products {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-statistics {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .card-categories {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .card-users {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <h1 class="admin-title">
            <i class="fa fa-dashboard"></i> Trang Quản Trị
        </h1>

        <div class="admin-grid">
            <a href="/admin/products" class="admin-card card-products">
                <i class="fa fa-shopping-bag"></i>
                <h3>Quản Lý Sản Phẩm</h3>
            </a>

            <a href="/admin/statistics" class="admin-card card-statistics">
                <i class="fa fa-bar-chart"></i>
                <h3>Thống Kê</h3>
            </a>

            <a href="/admin/categories" class="admin-card card-categories">
                <i class="fa fa-list"></i>
                <h3>Quản Lý Danh Mục</h3>
            </a>

            <a href="/admin/users" class="admin-card card-users">
                <i class="fa fa-users"></i>
                <h3>Quản Lý Người Dùng</h3>
            </a>
        </div>

        <a href="/" class="home-btn">
            <i class="fa fa-home"></i> Về Trang Chủ
        </a>
    </div>
</body>

</html>