<?php

namespace App\Models;

use PDO;

class Category
{
    protected $pdo;
    protected $table = 'categories';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY category_id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh mục theo ID
     */
    public function findById($category_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh mục với phân trang (Admin)
     */
    public function getPaginated($page = 1, $itemsPerPage = 10)
    {
        $page = max(1, (int)$page);
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số danh mục
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $countStmt = $this->pdo->query($countSql);
        $totalItems = $countStmt->fetch(PDO::FETCH_OBJ)->total;

        // Lấy danh mục của trang hiện tại
        $sql = "SELECT * FROM {$this->table} ORDER BY category_id ASC LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

        $totalPages = ceil($totalItems / $itemsPerPage);

        return [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    /**
     * Tạo danh mục mới (Admin)
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (category_id, category_name, category_desc)
                VALUES (?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['category_id'],
            $data['category_name'],
            $data['category_desc'] ?? null
        ]);
    }

    /**
     * Cập nhật danh mục (Admin)
     */
    public function update($category_id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                category_name = ?,
                category_desc = ?
                WHERE category_id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['category_name'],
            $data['category_desc'],
            $category_id
        ]);
    }

    /**
     * Xóa danh mục (Admin)
     */
    public function delete($category_id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE category_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$category_id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Tạo ID danh mục
     */
    public function generateId()
    {
        // Lấy tất cả category_id hiện có và tìm số lớn nhất
        $sql = "SELECT category_id FROM {$this->table} WHERE category_id LIKE 'CAT%' ORDER BY category_id DESC LIMIT 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result && preg_match('/CAT(\d+)/', $result->category_id, $matches)) {
            $nextId = (int)$matches[1] + 1;
        } else {
            $nextId = 1;
        }

        return 'CAT' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Kiểm tra xem danh mục có được tham chiếu không
     */
    public function isReferenced($category_id)
    {
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetch(PDO::FETCH_OBJ)->count > 0;
    }
}
