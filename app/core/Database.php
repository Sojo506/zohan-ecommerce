<!-- Este archivo se encarga de manejar la conexión a la base de datos utilizando PDO -->
<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = Env::get('DB_HOST', 'zohan-ecommerce-isaacmasis-a8e9.i.aivencloud.com');
        $db   = Env::get('DB_NAME', 'zohan_tech_store');
        $user = Env::get('DB_USER', 'avnadmin');
        $pass = Env::get('DB_PASS', 'AVNS_peuFS92U1mt0xcvo0qN');
        $charset = Env::get('DB_CHARSET', 'utf8mb4');

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

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
