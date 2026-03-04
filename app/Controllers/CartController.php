<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;

class CartController extends Controller
{
    protected $product;
    protected $order;

    public function __construct()
    {
        parent::__construct();
        $this->product = new Product(PDO());
        $this->order = new Order(PDO());
    }

    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        $cartItems = Cart::getAll();
        $total = Cart::getTotal();

        $data = [
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->sendPage('cart/index', $data);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (AJAX)
     */
    public function add()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit();
        }

        // CSRF validation for AJAX requests
        if (!$this->verifyCsrf()) {
            echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
            exit();
        }

        $product_id = $_POST['product_id'] ?? null;
        $quantity = intval($_POST['quantity'] ?? 1);

        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit();
        }

        // Lấy thông tin sản phẩm từ database
        $product = $this->product->findById($product_id);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit();
        }

        // Kiểm tra tồn kho
        if ($product->stock_quantity < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock']);
            exit();
        }

        // Thêm vào giỏ hàng
        Cart::add(
            $product->product_id,
            $product->product_name,
            $product->product_price,
            $product->image_urls,
            $quantity
        );

        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => Cart::count()
        ]);
        exit();
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit();
        }

        // CSRF validation for AJAX requests
        if (!$this->verifyCsrf()) {
            echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
            exit();
        }

        $product_id = $_POST['product_id'] ?? null;
        $quantity = intval($_POST['quantity'] ?? 0);

        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit();
        }

        Cart::update($product_id, $quantity);

        echo json_encode([
            'success' => true,
            'message' => 'Cart updated successfully',
            'total' => Cart::getTotal(),
            'cart_count' => Cart::count()
        ]);
        exit();
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit();
        }

        // CSRF validation for AJAX requests
        if (!$this->verifyCsrf()) {
            echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
            exit();
        }

        $product_id = $_POST['product_id'] ?? null;

        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit();
        }

        Cart::remove($product_id);

        echo json_encode([
            'success' => true,
            'message' => 'Product removed from cart',
            'total' => Cart::getTotal(),
            'cart_count' => Cart::count()
        ]);
        exit();
    }

    /**
     * Xóa tất cả sản phẩm trong giỏ hàng
     */
    public function clear()
    {
        Cart::clear();
        redirect('/cart');
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng (AJAX)
     */
    public function count()
    {
        header('Content-Type: application/json');
        echo json_encode(['count' => Cart::count()]);
        exit();
    }

    /**
     * Trang thanh toán
     */
    public function checkout()
    {
        // Kiểm tra đăng nhập
        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['redirect_after_login'] = '/cart/checkout';
            $_SESSION['messages'] = ['Vui lòng đăng nhập để tiếp tục thanh toán'];
            redirect('/login');
            return;
        }

        if (Cart::isEmpty()) {
            redirect('/', ['error' => 'Your cart is empty']);
            return;
        }

        $cartItems = Cart::getAll();
        $total = Cart::getTotal();

        $data = [
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->sendPage('cart/checkout', $data);
    }

    /**
     * Xử lý thanh toán
     */
    public function processCheckout()
    {
        $this->verifyCsrfOrFail();
        
        // Kiểm tra đăng nhập
        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['redirect_after_login'] = '/cart/checkout';
            $_SESSION['messages'] = ['Vui lòng đăng nhập để tiếp tục thanh toán'];
            redirect('/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/cart/checkout');
            return;
        }

        if (Cart::isEmpty()) {
            redirect('/', ['error' => 'Your cart is empty']);
            return;
        }

        // Lấy thông tin thanh toán từ form
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $payment_method = $_POST['payment_method'] ?? 'cod';

        // Validate dữ liệu
        if (empty($fullname) || empty($email) || empty($phone) || empty($address)) {
            redirect('/cart/checkout', ['error' => 'Please fill in all required fields']);
            return;
        }

        try {
            // Lấy giỏ hàng
            $cartItems = Cart::getAll();
            $total = Cart::getTotal();

            // Tính tổng số lượng sản phẩm
            $totalQuantity = 0;
            foreach ($cartItems as $item) {
                $totalQuantity += $item['quantity'];
            }

            // Chuẩn bị dữ liệu order
            $orderData = [
                'product_quantity' => $totalQuantity,
                'total_amount' => $total,
                'status' => 'success',
                'user_id' => AUTHGUARD()->user()->user_id,
                'items' => []
            ];

            // Chuẩn bị order items
            foreach ($cartItems as $item) {
                $orderData['items'][] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['product_price']
                ];
            }

            // Lưu đơn hàng vào database
            $orderId = $this->order->create($orderData);

            // Xóa giỏ hàng sau khi thanh toán thành công
            Cart::clear();

            redirect('/', ['success' => 'Order placed successfully! Your order ID is: ' . $orderId]);

        } catch (\Exception $e) {
            redirect('/cart/checkout', ['error' => 'Failed to place order. Please try again.']);
        }
    }
}

