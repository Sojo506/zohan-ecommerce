<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        // Reutiliza la misma conexión PDO durante toda la petición.
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = Env::get('DB_HOST', '127.0.0.1');
        $db   = Env::get('DB_NAME', 'zohan_tech_store');
        $user = Env::get('DB_USER', 'root');
        $pass = Env::get('DB_PASS', 'Plkmqaz1209');
        $charset = Env::get('DB_CHARSET', 'utf8mb4');

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

        // Configuración base para trabajar con excepciones y resultados asociativos.
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $user, $pass, $options);
            return self::$pdo;
        } catch (PDOException $e) {
            // En producción esto va a log y se muestra un mensaje genérico
            die("Error de conexión a BD: " . $e->getMessage());
        }
    }
}
