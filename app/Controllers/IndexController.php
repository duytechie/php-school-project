<?php

namespace App\Controllers;

use App\Models\Product;

class IndexController extends Controller
{
    protected $product;

    public function __construct()
    {
        parent::__construct();
        $this->product = new Product(PDO());
    }

    public function index()
    {
        // Lấy số trang từ query string, mặc định là trang 1
        $page = $_GET['page'] ?? 1;

        // Lấy sản phẩm với phân trang, 12 sản phẩm mỗi trang
        $paginationData = $this->product->getPaginated($page, 12);

        $data = [
            'products' => $paginationData['products'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $paginationData['itemsPerPage']
        ];

        $this->sendPage('index', $data);
    }
}
