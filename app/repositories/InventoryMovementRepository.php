<?php

class InventoryMovementRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO MOVIMIENTO_INVENTARIO_TB
                (ID_PRODUCTO,ID_TIPO_MOVIMIENTO,CANTIDAD,MOTIVO,ID_ESTADO)
                VALUES(:product,:type,:quantity,:reason,1)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':product' => $data['product'],
            ':type' => $data['type'],
            ':quantity' => $data['quantity'],
            ':reason' => $data['reason']
        ]);
    }

    public function all()
    {
        $sql = "SELECT 
                    M.ID_MOVIMIENTO,
                    P.NOMBRE,
                    T.NOMBRE AS TIPO,
                    M.CANTIDAD,
                    M.MOTIVO,
                    M.FECHA_MOVIMIENTO
                FROM MOVIMIENTO_INVENTARIO_TB M
                JOIN PRODUCTO_TB P ON P.ID_PRODUCTO = M.ID_PRODUCTO
                JOIN TIPO_MOVIMIENTO_TB T ON T.ID_TIPO_MOVIMIENTO = M.ID_TIPO_MOVIMIENTO
                WHERE M.ID_ESTADO = 1
                ORDER BY M.FECHA_MOVIMIENTO DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function getTypes()
    {
        return $this->db->query("
            SELECT * FROM TIPO_MOVIMIENTO_TB
            WHERE ID_ESTADO = 1
        ")->fetchAll();
    }
}
