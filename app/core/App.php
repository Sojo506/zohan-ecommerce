<?php

class App
{
    public static function config(string $key)
    {
        static $config = null;
        if ($config === null) {
            $config = require __DIR__ . '/../../config/config.php';
        }
        return $config[$key] ?? null;
    }

    public static function url(string $path): string
    {
        $base = self::config('BASE_URL');
        if ($path === '') $path = '/';
        if ($path[0] !== '/') $path = '/' . $path;
        return $base . $path;
    }
}
