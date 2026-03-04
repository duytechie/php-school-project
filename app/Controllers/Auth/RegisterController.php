<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct()
    {
        if (AUTHGUARD()->isUserLoggedIn()) {
            redirect('/');
        }

        parent::__construct();
    }

    public function create()
    {
        $data = [
            'old' => $this->getSavedFormValues(),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('auth/register', $data);
    }

    public function store()
    {
        $this->verifyCsrfOrFail();
        
        $this->saveFormValues($_POST, ['password', 'password_confirmation']);

        $data = $this->filterUserData($_POST);
        $newUser = new User(PDO());
        $model_errors = $newUser->validate($data);

        if (empty($model_errors)) {
            $newUser->fill($data)->save();

            $messages = ['success' => 'Account created successfully! Please login.'];
            redirect('/login', ['messages' => $messages]);
        }

        // Invalid data...
        redirect('/register', ['errors' => $model_errors]);
    }

    protected function filterUserData(array $data)
    {
        return [
            'username' => trim($data['username'] ?? ''),
            'email' => filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'fullname' => trim($data['fullname'] ?? $data['username'] ?? ''),
            'password' => $data['password'] ?? null,
            'password_confirmation' => $data['password_confirmation'] ?? null
        ];
    }
}

