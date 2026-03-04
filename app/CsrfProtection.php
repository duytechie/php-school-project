<?php

namespace App;

/**
 * Class CsrfProtection
 * Cung cấp bảo vệ CSRF cho ứng dụng bằng cách tạo và xác thực token
 */
class CsrfProtection
{
    /**
     * Tên của token trong session
     */
    const TOKEN_NAME = 'csrf_token';

    /**
     * Thời gian sống của token (giây) - mặc định 1 giờ
     */
    const TOKEN_LIFETIME = 3600;

    /**
     * Tạo một CSRF token mới và lưu vào session
     * 
     * @return string Token đã được tạo
     */
    public static function generateToken(): string
    {
        // Tạo token ngẫu nhiên an toàn
        $token = bin2hex(random_bytes(32));

        // Lưu token và thời gian tạo vào session
        $_SESSION[self::TOKEN_NAME] = [
            'token' => $token,
            'created_at' => time()
        ];

        return $token;
    }

    /**
     * Lấy token hiện tại hoặc tạo mới nếu chưa có
     * 
     * @return string
     */
    public static function getToken(): string
    {
        // Nếu chưa có token hoặc token đã hết hạn, tạo mới
        if (!self::hasValidToken()) {
            return self::generateToken();
        }

        return $_SESSION[self::TOKEN_NAME]['token'];
    }

    /**
     * Kiểm tra xem có token hợp lệ không
     * 
     * @return bool
     */
    public static function hasValidToken(): bool
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            return false;
        }

        $tokenData = $_SESSION[self::TOKEN_NAME];

        // Kiểm tra token có đủ thông tin
        if (!isset($tokenData['token']) || !isset($tokenData['created_at'])) {
            return false;
        }

        // Kiểm tra token có hết hạn không
        $currentTime = time();
        if (($currentTime - $tokenData['created_at']) > self::TOKEN_LIFETIME) {
            // Token đã hết hạn, xóa khỏi session
            unset($_SESSION[self::TOKEN_NAME]);
            return false;
        }

        return true;
    }

    /**
     * Xác thực token từ request
     * 
     * @param string|null $token Token cần xác thực
     * @return bool
     */
    public static function validateToken(?string $token): bool
    {
        // Kiểm tra token có được cung cấp không
        if (empty($token)) {
            return false;
        }

        // Kiểm tra có token trong session không
        if (!self::hasValidToken()) {
            return false;
        }

        // So sánh token an toàn (chống timing attack)
        $sessionToken = $_SESSION[self::TOKEN_NAME]['token'];
        return hash_equals($sessionToken, $token);
    }

    /**
     * Xác thực token từ POST request
     * 
     * @return bool
     */
    public static function validateRequest(): bool
    {
        $token = $_POST[self::TOKEN_NAME] ?? null;
        return self::validateToken($token);
    }

    /**
     * Xóa token khỏi session
     */
    public static function deleteToken(): void
    {
        if (isset($_SESSION[self::TOKEN_NAME])) {
            unset($_SESSION[self::TOKEN_NAME]);
        }
    }

    /**
     * Tạo HTML input field cho CSRF token
     * 
     * @return string
     */
    public static function getTokenField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Kiểm tra token và throw exception nếu không hợp lệ
     * 
     * @throws CsrfException
     */
    public static function verifyOrFail(): void
    {
        if (!self::validateRequest()) {
            throw new CsrfException('CSRF token validation failed. Please refresh the page and try again.');
        }
    }
}

