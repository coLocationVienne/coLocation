<?php

namespace App\Core;

class Config {
    const BASE_URL = '/coLocation';

    public static function url($path = '') {
        return self::BASE_URL . '/' . ltrim($path, '/');
    }

    public static function asset($path = '') {
        return self::BASE_URL . '/assets/' . ltrim($path, '/');
    }
}
