<?php

namespace App\Models;

use PDO;

class Order
{
    protected $pdo;
    protected $table = 'orders';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Tạo order_id duy nhất
     */
    public function generateOrderId()
    {
        $sql = "SELECT order_id FROM {$this->table} WHERE order_id LIKE 'O%' ORDER BY order_id DESC LIMIT 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && preg_match('/O(\d+)/', $result['order_id'], $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'O' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Tạo item_id duy nhất
     */
    public function generateItemId()
    {
        $sql = "SELECT item_id FROM order_items WHERE item_id LIKE 'I%' ORDER BY item_id DESC LIMIT 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && preg_match('/I(\d+)/', $result['item_id'], $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'I' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Tạo đơn hàng mới
     */
    public function create($data)
    {
        try {
            $this->pdo->beginTransaction();

            // Tạo order
            $orderId = $this->generateOrderId();

            $sql = "INSERT INTO {$this->table} 
                    (order_id, product_quantity, total_amount, order_date, status, user_id, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $orderId,
                $data['product_quantity'],
                $data['total_amount'],
                date('Y-m-d H:i:s'),
                $data['status'] ?? 'success',
                $data['user_id'] ?? 'U001' // Default user nếu chưa login
            ]);

            // Tạo order items
            if (!empty($data['items'])) {
                $itemSql = "INSERT INTO order_items (item_id, order_id, product_id, quantity, price)
                           VALUES (?, ?, ?, ?, ?)";
                $itemStmt = $this->pdo->prepare($itemSql);

                foreach ($data['items'] as $item) {
                    $itemId = $this->generateItemId();
                    $itemStmt->execute([
                        $itemId,
                        $orderId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['price']
                    ]);
                }
            }

            $this->pdo->commit();
            return $orderId;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Lấy đơn hàng theo ID
     */
    public function findById($order_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE order_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Lấy tất cả đơn hàng
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy đơn hàng theo user
     */
    public function getByUser($user_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

