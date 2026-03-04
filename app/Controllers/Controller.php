<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\CsrfProtection;
use App\CsrfException;

class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new Engine(ROOTDIR . 'app/views');
    }

    /**
     * Xác thực CSRF token từ request
     * 
     * @return bool
     */
    protected function verifyCsrf(): bool
    {
        return CsrfProtection::validateRequest();
    }

    /**
     * Xác thực CSRF token hoặc trả về lỗi 403
     */
    protected function verifyCsrfOrFail(): void
    {
        if (!$this->verifyCsrf()) {
            http_response_code(403);
            $this->sendPage('errors/403', [
                'message' => 'CSRF token validation failed. Please refresh the page and try again.'
            ]);
        }
    }

    public function sendPage($page, array $data = [])
    {
        exit($this->view->render($page, $data));
    }

    protected function saveFormValues(array $data, array $except = [])
    {
        $form = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, $except, true)) {
                $form[$key] = $value;
            }
        }
        $_SESSION['form'] = $form;
    }

    protected function getSavedFormValues()
    {
        return session_get_once('form', []);
    }

    public function sendNotFound()
    {
        http_response_code(404);
        $this->sendPage('errors/404');
    }

    protected function requireAdmin()
    {
        if (!AUTHGUARD()->isAdmin()) {
            http_response_code(403);
            $this->sendPage('errors/403');
        }
    }

    public function sendForbidden()
    {
        http_response_code(403);
        $this->sendPage('errors/403');
    }
}
