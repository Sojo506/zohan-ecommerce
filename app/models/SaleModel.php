<?php

class SaleModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT 
                    V.ID_VENTA,
                    V.FECHA_VENTA,
                    E.NOMBRE AS ESTADO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO
                FROM VENTA_TB V
                JOIN CUENTA_TB C ON C.ID_CUENTA = V.ID_CUENTA
                JOIN USUARIO_TB U ON U.IDENTIFICACION = C.IDENTIFICACION
                JOIN ESTADO_TB E ON E.ID_ESTADO = V.ID_ESTADO
                ORDER BY V.FECHA_VENTA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT
                    V.*,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    U.APELLIDO_MATERNO,
                    E.NOMBRE AS ESTADO_NOMBRE
                FROM VENTA_TB V
                JOIN CUENTA_TB C
                    ON C.ID_CUENTA = V.ID_CUENTA
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                LEFT JOIN ESTADO_TB E
                    ON E.ID_ESTADO = V.ID_ESTADO
                WHERE V.ID_VENTA = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function products($saleId)
    {
        $sql = "SELECT 
                    P.NOMBRE,
                    VP.CANTIDAD,
                    VP.PRECIO
                FROM VENTA_PRODUCTO_TB VP
                JOIN PRODUCTO_TB P 
                    ON P.ID_PRODUCTO = VP.ID_PRODUCTO
                WHERE VP.ID_VENTA = :sale";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sale' => $saleId]);

        return $stmt->fetchAll();
    }
}
