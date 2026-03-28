<?php

// Punto único de carga: registra Composer si existe y resuelve clases propias por convención.
$vendorAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

spl_autoload_register(function (string $class): void {
    static $directories = [
        __DIR__ . '/app/core/',
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
        __DIR__ . '/app/services/',
        __DIR__ . '/app/helpers/',
    ];

    // Las dependencias con namespace las atiende Composer.
    if (str_contains($class, '\\')) {
        return;
    }

    foreach ($directories as $directory) {
        $path = $directory . $class . '.php';

        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});
