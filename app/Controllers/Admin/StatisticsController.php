<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Controllers\Controller;

class StatisticsController extends Controller
{
    protected $user;
    protected $product;
    protected $category;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->user = new User(PDO());
        $this->product = new Product(PDO());
        $this->category = new Category(PDO());
    }

    /**
     * Hiển thị dashboard thống kê
     */
    public function index()
    {
        $pdo = PDO();

        // Thống kê người dùng
        $userCount = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch(\PDO::FETCH_OBJ)->count;
        $adminCount = $pdo->query("SELECT COUNT(*) as count FROM users WHERE roles = 'Admin'")->fetch(\PDO::FETCH_OBJ)->count;
        $customerCount = $pdo->query("SELECT COUNT(*) as count FROM users WHERE roles = 'Customer'")->fetch(\PDO::FETCH_OBJ)->count;

        // Thống kê sản phẩm
        $productCount = $pdo->query("SELECT COUNT(*) as count FROM products")->fetch(\PDO::FETCH_OBJ)->count;
        $inStockCount = $pdo->query("SELECT COUNT(*) as count FROM products WHERE product_status = 'in_stock'")->fetch(\PDO::FETCH_OBJ)->count;
        $outOfStockCount = $pdo->query("SELECT COUNT(*) as count FROM products WHERE product_status = 'out_of_stock'")->fetch(\PDO::FETCH_OBJ)->count;

        // Thống kê danh mục
        $categoryCount = $pdo->query("SELECT COUNT(*) as count FROM categories")->fetch(\PDO::FETCH_OBJ)->count;

        // Thống kê đơn hàng
        $orderCount = $pdo->query("SELECT COUNT(*) as count FROM orders")->fetch(\PDO::FETCH_OBJ)->count;
        $successOrderCount = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'success'")->fetch(\PDO::FETCH_OBJ)->count;
        $failedOrderCount = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'failed'")->fetch(\PDO::FETCH_OBJ)->count;

        // Doanh thu
        $revenue = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'success'")->fetch(\PDO::FETCH_OBJ)->total;
        $revenue = $revenue ?? 0;

        // Top 5 sản phẩm bán chạy
        $topProducts = $pdo->query("
            SELECT p.product_id, p.product_name, SUM(oi.quantity) as total_quantity
            FROM products p
            JOIN order_items oi ON p.product_id = oi.product_id
            GROUP BY p.product_id, p.product_name
            ORDER BY total_quantity DESC
            LIMIT 5
        ")->fetchAll(\PDO::FETCH_OBJ);

        // Thống kê sản phẩm theo danh mục
        $productsByCategory = $pdo->query("
            SELECT c.category_name, COUNT(p.product_id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.category_id = p.category_id
            GROUP BY c.category_id, c.category_name
        ")->fetchAll(\PDO::FETCH_OBJ);

        // Thống kê đơn hàng theo tháng (dữ liệu 12 tháng gần nhất)
        $ordersByMonth = $pdo->query("
            SELECT DATE_TRUNC('month', order_date)::date as month, COUNT(*) as order_count, SUM(total_amount) as month_revenue
            FROM orders
            WHERE order_date >= CURRENT_DATE - INTERVAL '12 months'
            GROUP BY DATE_TRUNC('month', order_date)
            ORDER BY month ASC
        ")->fetchAll(\PDO::FETCH_OBJ);

        $data = [
            'stats' => [
                'userCount' => $userCount,
                'adminCount' => $adminCount,
                'customerCount' => $customerCount,
                'productCount' => $productCount,
                'inStockCount' => $inStockCount,
                'outOfStockCount' => $outOfStockCount,
                'categoryCount' => $categoryCount,
                'orderCount' => $orderCount,
                'successOrderCount' => $successOrderCount,
                'failedOrderCount' => $failedOrderCount,
                'revenue' => $revenue
            ],
            'topProducts' => $topProducts,
            'productsByCategory' => $productsByCategory,
            'ordersByMonth' => $ordersByMonth
        ];

        $this->sendPage('admin/statistics/index', $data);
    }
}
