<?php

namespace App;

use App\Models\User;

class SessionGuard
{
    protected $user;

    public function login(User $user, array $credentials): bool
    {
        $verified = password_verify($credentials['password'], $user->password_hash);
        if ($verified) {
            $_SESSION['user_id'] = $user->user_id;
        }
        return $verified;
    }

    public function user(): ?User
    {
        if (!$this->user && $this->isUserLoggedIn()) {
            $this->user = (new User(PDO()))->where('user_id', $_SESSION['user_id']);
        }
        return $this->user;
    }

    public function logout(): void
    {
        $this->user = null;
        CsrfProtection::deleteToken();
        session_unset();
        session_destroy();
    }

    public function isUserLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin(): bool
    {
        if (!$this->isUserLoggedIn()) {
            return false;
        }
        $user = $this->user();
        return $user && $user->roles === 'Admin';
    }
}

