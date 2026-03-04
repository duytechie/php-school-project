<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Controllers\Controller;

class ProductController extends Controller
{
    protected $product;
    protected $category;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->product = new Product(PDO());
        $this->category = new Category(PDO());
    }

    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $paginationData = $this->product->getPaginated($page, 10);

        $data = [
            'products' => $paginationData['products'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $paginationData['itemsPerPage'],
            'messages' => session_get_once('messages'),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('admin/product/index', $data);
    }

    /**
     * Hiển thị form tạo sản phẩm
     */
    public function create()
    {
        $categories = $this->category->getAll();

        $data = [
            'categories' => $categories,
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/product/create', $data);
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store()
    {
        $this->verifyCsrfOrFail();
        
        $data = [
            'product_name' => $_POST['product_name'] ?? '',
            'product_desc' => $_POST['product_desc'] ?? '',
            'product_price' => (int) ($_POST['product_price'] ?? 0),
            'stock_quantity' => (int) ($_POST['stock_quantity'] ?? 0),
            'product_status' => $_POST['product_status'] ?? 'in_stock',
            'user_id' => AUTHGUARD()->user()->user_id,
            'category_id' => $_POST['category_id'] ?? '',
        ];

        $errors = $this->validateProduct($data);

        // Upload hình ảnh
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = $this->uploadImage($_FILES['image']);
            if ($uploadResult['success']) {
                $data['image_urls'] = $uploadResult['filename'];
            } else {
                $errors['image'] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $this->saveFormValues($_POST, ['password']);
            redirect('/admin/products/create', ['errors' => $errors]);
        }

        // Tạo product_id
        $data['product_id'] = $this->product->generateId();

        if ($this->product->create($data)) {
            redirect('/admin/products', ['messages' => ['Tạo sản phẩm thành công!']]);
        } else {
            redirect('/admin/products/create', ['errors' => ['Tạo sản phẩm thất bại!']]);
        }
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($product_id)
    {
        $product = $this->product->findById($product_id);
        $categories = $this->category->getAll();

        if (!$product) {
            $this->sendNotFound();
        }

        $data = [
            'product' => $product,
            'categories' => $categories,
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/product/edit', $data);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update($product_id)
    {
        $this->verifyCsrfOrFail();
        $product = $this->product->findById($product_id);

        if (!$product) {
            $this->sendNotFound();
        }

        $data = [
            'product_name' => $_POST['product_name'] ?? '',
            'product_desc' => $_POST['product_desc'] ?? '',
            'product_price' => (int) ($_POST['product_price'] ?? 0),
            'stock_quantity' => (int) ($_POST['stock_quantity'] ?? 0),
            'product_status' => $_POST['product_status'] ?? 'in_stock',
            'category_id' => $_POST['category_id'] ?? '',
            'image_urls' => $product->image_urls
        ];

        $errors = $this->validateProduct($data);

        // Upload hình ảnh mới nếu có
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = $this->uploadImage($_FILES['image']);
            if ($uploadResult['success']) {
                // Xóa ảnh cũ nếu có
                if ($product->image_urls && file_exists(ROOTDIR . 'public/images/products/' . $product->image_urls)) {
                    unlink(ROOTDIR . 'public/images/products/' . $product->image_urls);
                }
                $data['image_urls'] = $uploadResult['filename'];
            } else {
                $errors['image'] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            redirect("/admin/products/{$product_id}/edit", ['errors' => $errors]);
        }

        if ($this->product->update($product_id, $data)) {
            redirect('/admin/products', ['messages' => ['Cập nhật sản phẩm thành công!']]);
        } else {
            redirect("/admin/products/{$product_id}/edit", ['errors' => ['Cập nhật sản phẩm thất bại!']]);
        }
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($product_id)
    {
        $this->verifyCsrfOrFail();
        $product = $this->product->findById($product_id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        // Nếu sản phẩm đang được tham chiếu ở bảng order_items thì không cho xóa
        if ($this->product->isReferenced($product_id)) {
            redirect('/admin/products', ['errors' => ['Sản phẩm đang có trong đơn hàng, không thể xóa']]);
            return;
        }

        // Xóa hình ảnh (nếu có)
        if ($product->image_urls && file_exists(ROOTDIR . 'public/images/products/' . $product->image_urls)) {
            @unlink(ROOTDIR . 'public/images/products/' . $product->image_urls);
        }

        if ($this->product->delete($product_id)) {
            redirect('/admin/products', ['messages' => ['Xóa sản phẩm thành công!']]);
        } else {
            redirect('/admin/products', ['errors' => ['Xóa sản phẩm thất bại!']]);
        }
    }

    /**
     * Validate dữ liệu sản phẩm
     */
    protected function validateProduct($data)
    {
        $errors = [];

        if (empty($data['product_name'])) {
            $errors['product_name'] = 'Tên sản phẩm không được bỏ trống';
        }

        if (empty($data['product_price']) || $data['product_price'] < 0) {
            $errors['product_price'] = 'Giá phải lớn hơn 0';
        }

        if (empty($data['stock_quantity']) || $data['stock_quantity'] < 0) {
            $errors['stock_quantity'] = 'Số lượng phải lớn hơn 0';
        }

        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Danh mục không được bỏ trống';
        }

        return $errors;
    }

    /**
     * Upload hình ảnh
     */
    protected function uploadImage($file)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        // Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Lỗi upload hình ảnh'];
        }

        // Kiểm tra kích thước file
        if ($file['size'] > $maxFileSize) {
            return ['success' => false, 'error' => 'Hình ảnh quá lớn (tối đa 5MB)'];
        }

        // Kiểm tra phần mở rộng
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            return ['success' => false, 'error' => 'Định dạng hình ảnh không được hỗ trợ'];
        }

        // Tạo tên file duy nhất
        $uploadDir = ROOTDIR . 'public/images/products/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Không thể lưu hình ảnh'];
        }
    }
}
