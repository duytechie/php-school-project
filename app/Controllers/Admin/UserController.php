<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controllers\Controller;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->user = new User(PDO());
    }

    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $paginationData = $this->user->getPaginated($page, 10);

        $data = [
            'users' => $paginationData['users'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $paginationData['itemsPerPage'],
            'messages' => session_get_once('messages'),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('admin/user/index', $data);
    }

    /**
     * Hiển thị form tạo người dùng
     */
    public function create()
    {
        $data = [
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/user/create', $data);
    }

    /**
     * Lưu người dùng mới
     */
    public function store()
    {
        $this->verifyCsrfOrFail();

        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'fullname' => $_POST['fullname'] ?? '',
            'roles' => $_POST['roles'] ?? 'Customer',
            'password' => $_POST['password'] ?? ''
        ];

        $errors = $this->validateUser($data);

        if (!empty($errors)) {
            $this->saveFormValues($_POST, ['password']);
            redirect('/admin/users/create', ['errors' => $errors]);
        }

        $data['user_id'] = $this->user->generateId();
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);

        try {
            if ($this->user->createUser($data)) {
                redirect('/admin/users', ['messages' => ['Tạo người dùng thành công!']]);
            } else {
                redirect('/admin/users/create', ['errors' => ['Tạo người dùng thất bại!']]);
            }
        } catch (\PDOException $e) {
            // Kiểm tra lỗi duplicate key
            if ($e->getCode() == '23505' || strpos($e->getMessage(), 'duplicate key') !== false) {
                // Kiểm tra xem trùng username hay email
                if (strpos($e->getMessage(), 'users_username_key') !== false) {
                    $errors['username'] = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác';
                } elseif (strpos($e->getMessage(), 'users_email_key') !== false) {
                    $errors['email'] = 'Email đã tồn tại, vui lòng sử dụng email khác';
                } else {
                    $errors['general'] = 'Dữ liệu bị trùng lặp, vui lòng kiểm tra lại';
                }
                $this->saveFormValues($_POST, ['password']);
                redirect('/admin/users/create', ['errors' => $errors]);
            }

            // Lỗi khác không xử lý được
            throw $e;
        }
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit($user_id)
    {
        $user = $this->user->findById($user_id);

        if (!$user) {
            $this->sendNotFound();
        }

        $data = [
            'user' => $user,
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues()
        ];

        $this->sendPage('admin/user/edit', $data);
    }

    /**
     * Cập nhật người dùng
     */
    public function update($user_id)
    {
        $this->verifyCsrfOrFail();

        $user = $this->user->findById($user_id);

        if (!$user) {
            $this->sendNotFound();
        }

        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'fullname' => $_POST['fullname'] ?? '',
            'roles' => $_POST['roles'] ?? 'Customer'
        ];

        $errors = $this->validateUserUpdate($data, $user_id);

        if (!empty($errors)) {
            redirect("/admin/users/{$user_id}/edit", ['errors' => $errors]);
        }

        try {
            if ($this->user->updateUser($user_id, $data)) {
                redirect('/admin/users', ['messages' => ['Cập nhật người dùng thành công!']]);
            } else {
                redirect("/admin/users/{$user_id}/edit", ['errors' => ['Cập nhật người dùng thất bại!']]);
            }
        } catch (\PDOException $e) {
            // Kiểm tra lỗi duplicate key
            if ($e->getCode() == '23505' || strpos($e->getMessage(), 'duplicate key') !== false) {
                if (strpos($e->getMessage(), 'users_username_key') !== false) {
                    $errors['username'] = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác';
                } elseif (strpos($e->getMessage(), 'users_email_key') !== false) {
                    $errors['email'] = 'Email đã tồn tại, vui lòng sử dụng email khác';
                } else {
                    $errors['general'] = 'Dữ liệu bị trùng lặp, vui lòng kiểm tra lại';
                }
                redirect("/admin/users/{$user_id}/edit", ['errors' => $errors]);
            }

            // Lỗi khác không xử lý được
            throw $e;
        }
    }

    /**
     * Xóa người dùng
     */
    public function destroy($user_id)
    {
        $this->verifyCsrfOrFail();

        $user = $this->user->findById($user_id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
            exit;
        }

        if ($this->user->isReferenced($user_id)) {
            redirect('/admin/users', ['errors' => ['Người dùng đang có sản phẩm hoặc đơn hàng, không thể xóa']]);
            return;
        }

        if ($this->user->deleteUser($user_id)) {
            redirect('/admin/users', ['messages' => ['Xóa người dùng thành công!']]);
        } else {
            redirect('/admin/users', ['errors' => ['Xóa người dùng thất bại!']]);
        }
    }

    /**
     * Validate dữ liệu người dùng (tạo)
     */
    protected function validateUser($data)
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập không được bỏ trống';
        } else {
            // Kiểm tra xem username đã tồn tại chưa
            $existingUser = $this->user->where('username', $data['username']);
            if ($existingUser->isLoggedIn()) {
                $errors['username'] = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác';
            }
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được bỏ trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } else {
            // Kiểm tra xem email đã tồn tại chưa
            $existingUser = $this->user->where('email', $data['email']);
            if ($existingUser->isLoggedIn()) {
                $errors['email'] = 'Email đã tồn tại, vui lòng sử dụng email khác';
            }
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        return $errors;
    }

    /**
     * Validate dữ liệu người dùng (cập nhật)
     */
    protected function validateUserUpdate($data, $currentUserId = null)
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập không được bỏ trống';
        } else {
            // Kiểm tra xem username đã tồn tại chưa (ngoại trừ user hiện tại)
            $existingUser = $this->user->where('username', $data['username']);
            if ($existingUser->isLoggedIn() && $existingUser->user_id != $currentUserId) {
                $errors['username'] = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác';
            }
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được bỏ trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } else {
            // Kiểm tra xem email đã tồn tại chưa (ngoại trừ user hiện tại)
            $existingUser = $this->user->where('email', $data['email']);
            if ($existingUser->isLoggedIn() && $existingUser->user_id != $currentUserId) {
                $errors['email'] = 'Email đã tồn tại, vui lòng sử dụng email khác';
            }
        }

        return $errors;
    }
}
