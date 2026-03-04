<?php

namespace App\Models;

use PDO;

class Product
{
    protected $pdo;
    protected $table = 'products';

    public $product_id;
    public $product_name;
    public $product_desc;
    public $product_price;
    public $stock_quantity;
    public $product_status;
    public $user_id;
    public $category_id;
    public $image_urls;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả sản phẩm
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY product_id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy sản phẩm với phân trang
     */
    public function getPaginated($page = 1, $itemsPerPage = 10)
    {
        $page = max(1, (int)$page);
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số sản phẩm
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $countStmt = $this->pdo->query($countSql);
        $totalItems = $countStmt->fetch(PDO::FETCH_OBJ)->total;

        // Lấy sản phẩm của trang hiện tại
        $sql = "SELECT * FROM {$this->table} ORDER BY product_id DESC LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);

        $totalPages = ceil($totalItems / $itemsPerPage);

        return [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    /**
     * Lấy sản phẩm theo ID
     */
    public function findById($product_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Lấy sản phẩm theo danh mục
     */
    public function findByCategory($category_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Tạo sản phẩm mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (product_id, product_name, product_desc, product_price, stock_quantity, product_status, user_id, category_id, image_urls)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['product_id'],
            $data['product_name'],
            $data['product_desc'],
            $data['product_price'],
            $data['stock_quantity'],
            $data['product_status'] ?? 'in_stock',
            $data['user_id'],
            $data['category_id'],
            $data['image_urls'] ?? null
        ]);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update($product_id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                product_name = ?,
                product_desc = ?,
                product_price = ?,
                stock_quantity = ?,
                product_status = ?,
                category_id = ?,
                image_urls = ?
                WHERE product_id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['product_name'],
            $data['product_desc'],
            $data['product_price'],
            $data['stock_quantity'],
            $data['product_status'] ?? 'in_stock',
            $data['category_id'],
            $data['image_urls'] ?? null,
            $product_id
        ]);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete($product_id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE product_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$product_id]);
        } catch (\PDOException $e) {
            // Bắt lỗi ngoại lệ SQL (ví dụ: foreign key constraint) và trả về false
            return false;
        }
    }

    /**
     * Kiểm tra xem sản phẩm có đang được tham chiếu bởi bảng khác (ví dụ order_items)
     * Trả về true nếu có tham chiếu
     */
    public function isReferenced($product_id)
    {
        $sql = "SELECT COUNT(*) as cnt FROM order_items WHERE product_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['cnt']) && (int)$row['cnt'] > 0;
    }

    /**
     * Kiểm tra sản phẩm tồn tại
     */
    public function exists($product_id)
    {
        $product = $this->findById($product_id);
        return !empty($product);
    }

    /**
     * Tạo product_id duy nhất
     */
    public function generateId()
    {
        // Lấy product_id cuối cùng với format P + số
        $sql = "SELECT product_id FROM {$this->table} WHERE product_id LIKE 'P%' ORDER BY product_id DESC LIMIT 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && preg_match('/P(\d+)/', $result['product_id'], $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
