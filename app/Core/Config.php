<?php

namespace App\Core;

class Config {
    /**
     * The base URL of the application.
     * In Docker, this defaults to '/' (root).
     * In XAMPP, it might be '/coLocation'.
     */
    public static function getBaseUrl() {
        return getenv('APP_BASE_URL') ?: '/coLocation';
    }

    public static function url($path = '') {
        return rtrim(self::getBaseUrl(), '/') . '/' . ltrim($path, '/');
    }

    public static function asset($path = '') {
        return rtrim(self::getBaseUrl(), '/') . '/assets/' . ltrim($path, '/');
    }
}
