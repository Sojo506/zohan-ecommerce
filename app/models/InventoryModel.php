<?php

class InventoryModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT 
                    I.ID_INVENTARIO,
                    P.NOMBRE,
                    P.SKU,
                    I.STOCK,
                    P.STOCK_MINIMO
                FROM INVENTARIO_TB I
                JOIN PRODUCTO_TB P ON P.ID_PRODUCTO = I.ID_PRODUCTO
                WHERE I.ID_ESTADO = 1";

        return $this->db->query($sql)->fetchAll();
    }

    public function findByProduct($productId)
    {
        $sql = "SELECT * FROM INVENTARIO_TB
                WHERE ID_PRODUCTO = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $productId]);

        return $stmt->fetch();
    }

    public function updateStock($productId, $newStock)
    {
        $sql = "UPDATE INVENTARIO_TB
                SET STOCK = :stock
                WHERE ID_PRODUCTO = :product";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':stock' => $newStock,
            ':product' => $productId
        ]);
    }
}
