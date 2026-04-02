<?php

class ReportModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function salesOverview(): array
    {
        $sql = "SELECT
                    COUNT(*) AS total_invoices,
                    COALESCE(SUM(F.TOTAL), 0) AS total_revenue,
                    COALESCE(AVG(F.TOTAL), 0) AS average_ticket,
                    COALESCE(SUM(CASE
                        WHEN F.FECHA_FACTURA >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                        THEN F.TOTAL
                        ELSE 0
                    END), 0) AS revenue_last_30_days,
                    SUM(CASE
                        WHEN LOWER(E.NOMBRE) = 'pagada' THEN 1
                        ELSE 0
                    END) AS paid_invoices,
                    SUM(CASE
                        WHEN LOWER(E.NOMBRE) = 'pendiente' THEN 1
                        ELSE 0
                    END) AS pending_invoices
                FROM FACTURA_TB F
                JOIN ESTADO_TB E ON E.ID_ESTADO = F.ID_ESTADO";

        $result = $this->db->query($sql)->fetch();

        return $result ?: [];
    }

    public function monthlyRevenue(int $limit = 6): array
    {
        $limit = max(1, min($limit, 12));

        $sql = "SELECT
                    DATE_FORMAT(F.FECHA_FACTURA, '%Y-%m') AS period_key,
                    DATE_FORMAT(F.FECHA_FACTURA, '%b %Y') AS period_label,
                    COUNT(*) AS invoices,
                    COALESCE(SUM(F.TOTAL), 0) AS revenue
                FROM FACTURA_TB F
                GROUP BY DATE_FORMAT(F.FECHA_FACTURA, '%Y-%m'), DATE_FORMAT(F.FECHA_FACTURA, '%b %Y')
                ORDER BY period_key DESC
                LIMIT {$limit}";

        $rows = $this->db->query($sql)->fetchAll();

        return array_reverse($rows);
    }

    public function topSellingProducts(int $limit = 8): array
    {
        $limit = max(1, min($limit, 20));

        $sql = "SELECT
                    P.ID_PRODUCTO,
                    P.NOMBRE,
                    P.SKU,
                    SUM(VP.CANTIDAD) AS units_sold,
                    COALESCE(SUM(VP.CANTIDAD * VP.PRECIO), 0) AS revenue
                FROM VENTA_PRODUCTO_TB VP
                JOIN PRODUCTO_TB P ON P.ID_PRODUCTO = VP.ID_PRODUCTO
                GROUP BY P.ID_PRODUCTO, P.NOMBRE, P.SKU
                ORDER BY units_sold DESC, revenue DESC
                LIMIT {$limit}";

        return $this->db->query($sql)->fetchAll();
    }

    public function criticalInventory(int $limit = 10): array
    {
        $limit = max(1, min($limit, 20));

        $sql = "SELECT
                    P.ID_PRODUCTO,
                    P.NOMBRE,
                    P.SKU,
                    I.STOCK,
                    P.STOCK_MINIMO,
                    (P.STOCK_MINIMO - I.STOCK) AS shortage
                FROM INVENTARIO_TB I
                JOIN PRODUCTO_TB P ON P.ID_PRODUCTO = I.ID_PRODUCTO
                WHERE I.ID_ESTADO = 1
                  AND I.STOCK <= P.STOCK_MINIMO
                ORDER BY shortage DESC, I.STOCK ASC, P.NOMBRE ASC
                LIMIT {$limit}";

        return $this->db->query($sql)->fetchAll();
    }
}
