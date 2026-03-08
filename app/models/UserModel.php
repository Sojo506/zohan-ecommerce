<?php

class UserModel {

    private $db;

    public function __construct(){
        $this->db = Database::connection();
    }

    public function obtenerUsuario($identificacion)
    {
        $pdo = Database::connection();

        $sql = "SELECT 
                u.IDENTIFICACION,
                u.NOMBRE,
                u.APELLIDO_PATERNO,
                u.APELLIDO_MATERNO,
                u.FECHA_REGISTRO,
                c.CORREO
            FROM USUARIO_TB u
            LEFT JOIN CORREO_TB c 
                ON c.IDENTIFICACION = u.IDENTIFICACION
            WHERE u.IDENTIFICACION = :identificacion
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'identificacion' => $identificacion
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerStatsUsuario($identificacion)
    {
        $pdo = Database::connection();

        $sql = "SELECT 
                COUNT(DISTINCT v.ID_VENTA) AS total_compras,
                SUM(vp.CANTIDAD) AS productos_comprados,
                SUM(vp.CANTIDAD * vp.PRECIO) AS dinero_gastado
            FROM VENTA_TB v
            JOIN CUENTA_TB c ON c.ID_CUENTA = v.ID_CUENTA
            JOIN VENTA_PRODUCTO_TB vp ON vp.ID_VENTA = v.ID_VENTA
            WHERE c.IDENTIFICACION = :identificacion";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['identificacion' => $identificacion]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>