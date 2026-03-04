<style>
    .admin-navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .admin-navbar .nav-link {
        color: white !important;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-navbar .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .admin-navbar .nav-link.active {
        background: rgba(255, 255, 255, 0.3);
        font-weight: 600;
    }

    .admin-navbar .navbar-brand {
        color: white !important;
        font-weight: 700;
        font-size: 24px;
    }

    .admin-navbar .navbar-brand i {
        margin-right: 8px;
    }
</style>

<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin">
            <i class="fa fa-dashboard"></i>
            Admin Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            style="border-color: white;">
            <span class="navbar-toggler-icon" style="filter: brightness(0) invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'active' : '' ?>"
                        href="/admin/products">
                        <i class="fa fa-shopping-bag"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active' : '' ?>"
                        href="/admin/categories">
                        <i class="fa fa-list"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>"
                        href="/admin/users">
                        <i class="fa fa-users"></i> Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/statistics') !== false ? 'active' : '' ?>"
                        href="/admin/statistics">
                        <i class="fa fa-bar-chart"></i> Thống kê
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="fa fa-home"></i> Trang chủ
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>