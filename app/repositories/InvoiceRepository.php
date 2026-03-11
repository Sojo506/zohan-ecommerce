<?php

class InvoiceRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findBySale($saleId)
    {
        $sql = "SELECT * 
                FROM FACTURA_TB
                WHERE ID_VENTA = :sale";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sale' => $saleId]);

        return $stmt->fetch();
    }
}
