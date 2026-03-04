<?php

if (!function_exists('PDO')) {
    function PDO(): PDO
    {
        global $PDO;
        return $PDO;
    }
}

if (!function_exists('AUTHGUARD')) {
    function AUTHGUARD(): App\SessionGuard
    {
        global $AUTHGUARD;
        return $AUTHGUARD;
    }
}

if (!function_exists('dd')) {
    function dd($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        exit();
    }
}

if (!function_exists('redirect')) {
    function redirect($location, array $data = [])
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }

        header('Location: ' . $location, true, 302);
        exit();
    }
}

if (!function_exists('session_get_once')) {
    function session_get_once($name, $default = null)
    {
        $value = $default;
        if (isset($_SESSION[$name])) {
            $value = $_SESSION[$name];
            unset($_SESSION[$name]);
        }
        return $value;
    }
}

if (!function_exists('generate_user_id')) {
    function generate_user_id(): string
    {
        $pdo = PDO();
        $stmt = $pdo->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
        $result = $stmt->fetch();

        if ($result) {
            $lastId = intval(substr($result['user_id'], 1));
            return 'U' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        }

        return 'U001';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Lấy CSRF token hiện tại
     * 
     * @return string
     */
    function csrf_token(): string
    {
        return App\CsrfProtection::getToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Tạo HTML input field cho CSRF token
     * 
     * @return string
     */
    function csrf_field(): string
    {
        return App\CsrfProtection::getTokenField();
    }
}

if (!function_exists('verify_csrf')) {
    /**
     * Xác thực CSRF token từ request
     * 
     * @return bool
     */
    function verify_csrf(): bool
    {
        return App\CsrfProtection::validateRequest();
    }
}

if (!function_exists('verify_csrf_or_fail')) {
    /**
     * Xác thực CSRF token hoặc throw exception
     * 
     * @throws App\CsrfException
     */
    function verify_csrf_or_fail(): void
    {
        App\CsrfProtection::verifyOrFail();
    }
}

