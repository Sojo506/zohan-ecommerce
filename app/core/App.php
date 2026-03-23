<?php

class App
{
    public static function config(string $key)
    {
        static $config = null;

        // Cachea la configuración después de la primera carga para reutilizarla en helpers globales.
        if ($config === null) {
            $config = require __DIR__ . '/../../config/config.php';
        }

        return $config[$key] ?? null;
    }

    public static function url(string $path): string
    {
        $base = self::config('BASE_URL');

        if ($path === '') {
            $path = '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // BASE_URL ya trae ?url=, por lo que los query params deben anexarse con &
        if (strpos($path, '?') !== false) {
            [$cleanPath, $query] = explode('?', $path, 2);
            return $base . $cleanPath . '&' . $query;
        }

        return $base . $path;
    }
}
