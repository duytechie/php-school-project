<?php

namespace App\Controllers\Admin;

use App\Models\Category;
use App\Controllers\Controller;

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->category = new Category(PDO());
    }

    /**
     * Hiển thị danh sách danh mục
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $paginationData = $this->category->getPaginated($page, 10);

        $data = [
            'categories' => $paginationData['categories'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $paginationData['itemsPerPage'],
            'messages' => session_get_once('messages'),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('admin/category/index', $data);
    }

    /**
     * Hiển thị form tạo danh mục
     */
    public function create()
    {
        $data = [
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/category/create', $data);
    }

    /**
     * Lưu danh mục mới
     */
    public function store()
    {
        $this->verifyCsrfOrFail();
        
        $data = [
            'category_name' => $_POST['category_name'] ?? '',
            'category_desc' => $_POST['category_desc'] ?? ''
        ];

        $errors = $this->validateCategory($data);

        if (!empty($errors)) {
            $this->saveFormValues($_POST);
            redirect('/admin/categories/create', ['errors' => $errors]);
        }

        $data['category_id'] = $this->category->generateId();

        if ($this->category->create($data)) {
            redirect('/admin/categories', ['messages' => ['Tạo danh mục thành công!']]);
        } else {
            redirect('/admin/categories/create', ['errors' => ['Tạo danh mục thất bại!']]);
        }
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit($category_id)
    {
        $category = $this->category->findById($category_id);

        if (!$category) {
            $this->sendNotFound();
        }

        $data = [
            'category' => $category,
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/category/edit', $data);
    }

    /**
     * Cập nhật danh mục
     */
    public function update($category_id)
    {
        $this->verifyCsrfOrFail();
        
        $category = $this->category->findById($category_id);

        if (!$category) {
            $this->sendNotFound();
        }

        $data = [
            'category_name' => $_POST['category_name'] ?? '',
            'category_desc' => $_POST['category_desc'] ?? ''
        ];

        $errors = $this->validateCategory($data);

        if (!empty($errors)) {
            redirect("/admin/categories/{$category_id}/edit", ['errors' => $errors]);
        }

        if ($this->category->update($category_id, $data)) {
            redirect('/admin/categories', ['messages' => ['Cập nhật danh mục thành công!']]);
        } else {
            redirect("/admin/categories/{$category_id}/edit", ['errors' => ['Cập nhật danh mục thất bại!']]);
        }
    }

    /**
     * Xóa danh mục
     */
    public function destroy($category_id)
    {
        $this->verifyCsrfOrFail();
        
        $category = $this->category->findById($category_id);

        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
            exit;
        }

        if ($this->category->isReferenced($category_id)) {
            redirect('/admin/categories', ['errors' => ['Danh mục đang có sản phẩm, không thể xóa']]);
            return;
        }

        if ($this->category->delete($category_id)) {
            redirect('/admin/categories', ['messages' => ['Xóa danh mục thành công!']]);
        } else {
            redirect('/admin/categories', ['errors' => ['Xóa danh mục thất bại!']]);
        }
    }

    /**
     * Validate dữ liệu danh mục
     */
    protected function validateCategory($data)
    {
        $errors = [];

        if (empty($data['category_name'])) {
            $errors['category_name'] = 'Tên danh mục không được bỏ trống';
        }

        return $errors;
    }
}
