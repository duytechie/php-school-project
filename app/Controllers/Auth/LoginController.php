<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;

class LoginController extends Controller
{
    public function create()
    {
        if (AUTHGUARD()->isUserLoggedIn()) {
            redirect('/');
        }

        $data = [
            'messages' => session_get_once('messages'),
            'old' => $this->getSavedFormValues(),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('auth/login', $data);
    }

    public function store()
    {
        $this->verifyCsrfOrFail();
        
        $user_credentials = $this->filterUserCredentials($_POST);

        $errors = [];
        $user = (new User(PDO()))->where('email', $user_credentials['email']);

        if (empty($user->user_id)) {
            // User not found by email, try username
            $user = (new User(PDO()))->where('username', $user_credentials['email']);
        }

        if (empty($user->user_id)) {
            $errors['email'] = 'Invalid email/username or password.';
        } else if (AUTHGUARD()->login($user, $user_credentials)) {
            // Login successful - check if there's a redirect URL
            $redirectUrl = $_SESSION['redirect_after_login'] ?? '/';
            unset($_SESSION['redirect_after_login']);
            redirect($redirectUrl);
        } else {
            // Wrong password
            $errors['password'] = 'Invalid email/username or password.';
        }

        // Login failed: save form values except password
        $this->saveFormValues($_POST, ['password']);
        redirect('/login', ['errors' => $errors]);
    }

    public function destroy()
    {
        $this->verifyCsrfOrFail();
        
        AUTHGUARD()->logout();
        redirect('/login');
    }

    protected function filterUserCredentials(array $data)
    {
        $email = $data['email'] ?? '';
        // Check if it's an email or username
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        return [
            'email' => $email,
            'password' => $data['password'] ?? null
        ];
    }
}

