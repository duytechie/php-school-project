<?php

namespace App\Models;

use PDO;

class User
{
    private PDO $db;

    public string $user_id = '';
    public string $username = '';
    public string $email = '';
    public string $password_hash = '';
    public string $fullname = '';
    public string $roles = 'Customer';

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function where(string $column, string $value): User
    {
        $allowedColumns = ['user_id', 'username', 'email'];
        if (!in_array($column, $allowedColumns)) {
            return $this;
        }

        $statement = $this->db->prepare("SELECT * FROM users WHERE $column = :value");
        $statement->execute(['value' => $value]);
        $row = $statement->fetch();
        if ($row) {
            $this->fillFromDbRow($row);
        }
        return $this;
    }

    public function save(): bool
    {
        $result = false;

        if (!empty($this->user_id) && $this->exists()) {
            // Update existing user
            $statement = $this->db->prepare(
                'UPDATE users SET username = :username, email = :email, password_hash = :password_hash,
                fullname = :fullname, roles = :roles WHERE user_id = :user_id'
            );
            $result = $statement->execute([
                'user_id' => $this->user_id,
                'username' => $this->username,
                'email' => $this->email,
                'password_hash' => $this->password_hash,
                'fullname' => $this->fullname,
                'roles' => $this->roles
            ]);
        } else {
            // Insert new user
            $this->user_id = generate_user_id();
            $statement = $this->db->prepare(
                'INSERT INTO users (user_id, username, email, password_hash, fullname, roles)
                VALUES (:user_id, :username, :email, :password_hash, :fullname, :roles)'
            );
            $result = $statement->execute([
                'user_id' => $this->user_id,
                'username' => $this->username,
                'email' => $this->email,
                'password_hash' => $this->password_hash,
                'fullname' => $this->fullname,
                'roles' => $this->roles
            ]);
        }

        return $result;
    }

    public function fill(array $data): User
    {
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->fullname = $data['fullname'] ?? $data['username'] ?? '';
        $this->password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->roles = $data['roles'] ?? 'Customer';
        return $this;
    }

    private function fillFromDbRow(array $row): void
    {
        $this->user_id = $row['user_id'] ?? '';
        $this->username = $row['username'] ?? '';
        $this->email = $row['email'] ?? '';
        $this->password_hash = $row['password_hash'] ?? '';
        $this->fullname = $row['fullname'] ?? '';
        $this->roles = $row['roles'] ?? 'Customer';
    }

    private function exists(): bool
    {
        if (empty($this->user_id)) {
            return false;
        }
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users WHERE user_id = :user_id');
        $statement->execute(['user_id' => $this->user_id]);
        return $statement->fetchColumn() > 0;
    }

    private function isEmailInUse(string $email): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->execute(['email' => $email]);
        return $statement->fetchColumn() > 0;
    }

    private function isUsernameInUse(string $username): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $statement->execute(['username' => $username]);
        return $statement->fetchColumn() > 0;
    }

    public function validate(array $data): array
    {
        $errors = [];

        // Validate username
        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors['username'] = 'Username must be at least 3 characters.';
        } elseif (strlen($data['username']) > 20) {
            $errors['username'] = 'Username must not exceed 20 characters.';
        } elseif ($this->isUsernameInUse($data['username'])) {
            $errors['username'] = 'Username already in use.';
        }

        // Validate email
        if (!$data['email']) {
            $errors['email'] = 'Invalid email address.';
        } elseif ($this->isEmailInUse($data['email'])) {
            $errors['email'] = 'Email already in use.';
        }

        // Validate password
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['password'] = 'Password confirmation does not match.';
        }

        return $errors;
    }

    public function isLoggedIn(): bool
    {
        return !empty($this->user_id);
    }

    /**
     * Lấy tất cả người dùng (Admin)
     */
    public function getAll()
    {
        $statement = $this->db->query("SELECT * FROM users ORDER BY user_id DESC");
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy người dùng với phân trang (Admin)
     */
    public function getPaginated($page = 1, $itemsPerPage = 10)
    {
        $page = max(1, (int)$page);
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số người dùng
        $countStmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $totalItems = $countStmt->fetch(PDO::FETCH_OBJ)->total;

        // Lấy người dùng của trang hiện tại
        $sql = "SELECT * FROM users ORDER BY user_id DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        $totalPages = ceil($totalItems / $itemsPerPage);

        return [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    /**
     * Lấy người dùng theo ID (Admin)
     */
    public function findById($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Tạo ID người dùng
     */
    public function generateId()
    {
        // Lấy tất cả user_id hiện có và tìm số lớn nhất
        $sql = "SELECT user_id FROM users WHERE user_id LIKE 'U%' ORDER BY user_id DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result && preg_match('/U(\d+)/', $result->user_id, $matches)) {
            $nextId = (int)$matches[1] + 1;
        } else {
            $nextId = 1;
        }

        return 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Kiểm tra xem người dùng có được tham chiếu không
     */
    public function isReferenced($user_id)
    {
        // Kiểm tra trong products
        $sql1 = "SELECT COUNT(*) as count FROM products WHERE user_id = ?";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute([$user_id]);
        $products = $stmt1->fetch(PDO::FETCH_OBJ)->count;

        // Kiểm tra trong orders
        $sql2 = "SELECT COUNT(*) as count FROM orders WHERE user_id = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([$user_id]);
        $orders = $stmt2->fetch(PDO::FETCH_OBJ)->count;

        return $products > 0 || $orders > 0;
    }

    /**
     * Cập nhật người dùng (Admin)
     */
    public function updateUser($user_id, $data)
    {
        $sql = "UPDATE users SET 
                username = ?,
                email = ?,
                fullname = ?,
                roles = ?
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['fullname'],
            $data['roles'],
            $user_id
        ]);
    }

    /**
     * Xóa người dùng (Admin)
     */
    public function deleteUser($user_id)
    {
        try {
            $sql = "DELETE FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$user_id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Tạo người dùng mới (Admin)
     */
    public function createUser($data)
    {
        $sql = "INSERT INTO users 
                (user_id, username, email, password_hash, fullname, roles)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['username'],
            $data['email'],
            $data['password_hash'],
            $data['fullname'],
            $data['roles'] ?? 'Customer'
        ]);
    }
}
