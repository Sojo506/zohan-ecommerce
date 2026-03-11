<?php

class InvoiceRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT 
                    F.ID_FACTURA,
                    F.ID_VENTA,
                    F.IMPUESTO,
                    F.SUBTOTAL,
                    F.TOTAL,
                    F.FECHA_FACTURA,
                    E.NOMBRE AS ESTADO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO
                FROM FACTURA_TB F
                JOIN VENTA_TB V
                    ON V.ID_VENTA = F.ID_VENTA
                JOIN CUENTA_TB C
                    ON C.ID_CUENTA = V.ID_CUENTA
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                JOIN ESTADO_TB E
                    ON E.ID_ESTADO = F.ID_ESTADO
                ORDER BY F.FECHA_FACTURA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT 
                    F.*,
                    E.NOMBRE AS ESTADO
                FROM FACTURA_TB F
                JOIN ESTADO_TB E
                    ON E.ID_ESTADO = F.ID_ESTADO
                WHERE F.ID_FACTURA = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function saleProducts($invoiceId)
    {
        $sql = "SELECT
                    P.NOMBRE,
                    VP.CANTIDAD,
                    VP.PRECIO,
                    (VP.CANTIDAD * VP.PRECIO) AS SUBTOTAL_LINEA
                FROM FACTURA_TB F
                JOIN VENTA_TB V
                    ON V.ID_VENTA = F.ID_VENTA
                JOIN VENTA_PRODUCTO_TB VP
                    ON VP.ID_VENTA = V.ID_VENTA
                JOIN PRODUCTO_TB P
                    ON P.ID_PRODUCTO = VP.ID_PRODUCTO
                WHERE F.ID_FACTURA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $invoiceId]);

        return $stmt->fetchAll();
    }

    public function paypalPayment($invoiceId)
    {
        $sql = "SELECT
                    P.ID_PAGO,
                    P.PAYPAL_ORDER_ID,
                    P.PAYPAL_CAPTURE_ID,
                    P.TOTAL,
                    P.FECHA_REGISTRO,
                    M.NOMBRE AS MONEDA,
                    E.NOMBRE AS ESTADO
                FROM PAGO_PAYPAL_TB P
                LEFT JOIN MONEDA_TB M
                    ON M.ID_MONEDA = P.ID_MONEDA
                LEFT JOIN ESTADO_TB E
                    ON E.ID_ESTADO = P.ID_ESTADO
                WHERE P.ID_FACTURA = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $invoiceId]);

        return $stmt->fetch();
    }

    public function changeStatus($id, $status)
    {
        $sql = "UPDATE FACTURA_TB
                SET ID_ESTADO = :status
                WHERE ID_FACTURA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
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
