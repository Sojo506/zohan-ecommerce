<?php

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        // Lee el .env línea por línea y descarta espacios y comentarios.
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);

            // Quita comillas opcionales para aceptar valores como KEY="valor".
            $value = trim($value, "\"'");

            // Respeta variables ya definidas por el entorno del servidor.
            if ($key !== '' && getenv($key) === false) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $val = $_ENV[$key] ?? getenv($key);

        // Trata cadena vacía como "no configurado" para que el fallback siga funcionando.
        return ($val === false || $val === null || $val === '') ? $default : $val;
    }
}
