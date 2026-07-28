<?php

namespace App\Core;

class Token
{
    public static function generate()
    {
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['token'];
    }

    public static function check($token)
    {
        if (empty($_SESSION['token']) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['token'], $token);
    }

    public static function field()
    {
        return '<input type="hidden" name="token" value="' . htmlspecialchars(self::generate(), ENT_QUOTES, 'UTF-8') . '">';
    }
}