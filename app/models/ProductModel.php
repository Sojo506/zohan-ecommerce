<?php

class ProductModel {

    private $db;

    public function __construct(){
        $this->db = Database::connection();
    }

    public function obtenerFacturasUsuario($identificacion)
    {
        $pdo = Database::connection();

        $sql = "SELECT 
                f.ID_FACTURA,
                f.FECHA_FACTURA,
                f.TOTAL,
                COUNT(vp.ID_PRODUCTO) AS TOTAL_PRODUCTOS
            FROM FACTURA_TB f
            JOIN VENTA_TB v ON v.ID_VENTA = f.ID_VENTA
            JOIN CUENTA_TB c ON c.ID_CUENTA = v.ID_CUENTA
            LEFT JOIN VENTA_PRODUCTO_TB vp ON vp.ID_VENTA = v.ID_VENTA
            WHERE c.IDENTIFICACION = :identificacion
            GROUP BY f.ID_FACTURA, f.FECHA_FACTURA, f.TOTAL
            ORDER BY f.FECHA_FACTURA DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['identificacion' => $identificacion]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>