<?php

namespace App\Auth;

use App\Models\User;

class Auth
{
    public function user()
    {
        return User::find($this->check() ? $_SESSION['user'] : null);
    }
    
    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password)
    {
        // Grab the user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        // Verifiy password for that user
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}