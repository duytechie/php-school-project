<?php

namespace App\Models;

class Cart
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public static function add($product_id, $product_name, $product_price, $image_url, $quantity = 1)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'image_url' => $image_url,
                'quantity' => $quantity
            ];
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public static function update($product_id, $quantity)
    {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
                self::remove($product_id);
            }
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public static function remove($product_id)
    {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    /**
     * Lấy tất cả sản phẩm trong giỏ hàng
     */
    public static function getAll()
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Tính tổng giá trị giỏ hàng
     */
    public static function getTotal()
    {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['product_price'] * $item['quantity'];
            }
        }
        return $total;
    }

    /**
     * Đếm số lượng sản phẩm trong giỏ hàng
     */
    public static function count()
    {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }

        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Xóa tất cả sản phẩm trong giỏ hàng
     */
    public static function clear()
    {
        unset($_SESSION['cart']);
    }

    /**
     * Kiểm tra giỏ hàng có rỗng không
     */
    public static function isEmpty()
    {
        return empty($_SESSION['cart']);
    }
}

