<?php

class DashboardModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function stats()
    {
        $stats = [];

        // Productos activos
        $stats['products'] = $this->db
            ->query("SELECT COUNT(*) FROM PRODUCTO_TB WHERE ID_ESTADO = 1")
            ->fetchColumn();

        // Ventas totales
        $stats['sales'] = $this->db
            ->query("SELECT COUNT(*) FROM VENTA_TB")
            ->fetchColumn();

        // Usuarios registrados
        $stats['users'] = $this->db
            ->query("SELECT COUNT(*) FROM USUARIO_TB WHERE ID_ESTADO = 1")
            ->fetchColumn();

        // Inventario bajo
        $stats['low_stock'] = $this->db
            ->query("
                SELECT COUNT(*)
                FROM INVENTARIO_TB I
                JOIN PRODUCTO_TB P ON P.ID_PRODUCTO = I.ID_PRODUCTO
                WHERE I.STOCK <= P.STOCK_MINIMO
            ")
            ->fetchColumn();

        return $stats;
    }

    public function recentSales()
    {
        $sql = "
            SELECT
                V.ID_VENTA as id,
                CONCAT(U.NOMBRE,' ',U.APELLIDO_PATERNO) as user,
                F.TOTAL as total,
                V.FECHA_VENTA as date
            FROM VENTA_TB V
            JOIN FACTURA_TB F ON F.ID_VENTA = V.ID_VENTA
            JOIN CUENTA_TB C ON C.ID_CUENTA = V.ID_CUENTA
            JOIN USUARIO_TB U ON U.IDENTIFICACION = C.IDENTIFICACION
            ORDER BY V.FECHA_VENTA DESC
            LIMIT 5
        ";

        return $this->db->query($sql)->fetchAll();
    }
}
