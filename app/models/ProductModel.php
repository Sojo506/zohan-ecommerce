<?php

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    private function subconsultaImagen(): string
    {
        return "(
            SELECT ID_PRODUCTO, MIN(URL_IMAGE) AS URL_IMAGE
            FROM PRODUCTO_IMAGE_TB
            WHERE ID_ESTADO = 1
            GROUP BY ID_PRODUCTO
        )";
    }

    public function obtenerProductos(array $filtros = []): array
    {
        $sql = "SELECT
                    p.ID_PRODUCTO,
                    p.NOMBRE,
                    p.DESCRIPCION,
                    p.PRECIO,
                    c.ID_CATEGORIA,
                    c.NOMBRE AS CATEGORIA,
                    m.ID_MARCA,
                    m.NOMBRE AS MARCA,
                    img.URL_IMAGE
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                WHERE p.ID_ESTADO = 1";

        $params = [];

        if (!empty($filtros['categoria'])) {
            $categoria = trim((string)$filtros['categoria']);
            $sql .= " AND (
                        LOWER(c.NOMBRE) = :categoriaExacta
                        OR REPLACE(LOWER(c.NOMBRE), ' ', '') = :categoriaLimpia
                      )";
            $params[':categoriaExacta'] = strtolower($categoria);
            $params[':categoriaLimpia'] = str_replace(' ', '', strtolower($categoria));
        }

        if (!empty($filtros['marca'])) {
            $sql .= " AND m.ID_MARCA = :idMarca";
            $params[':idMarca'] = (int)$filtros['marca'];
        }

        if (!empty($filtros['q'])) {
            $sql .= " AND (p.NOMBRE LIKE :busqueda OR p.DESCRIPCION LIKE :busqueda)";
            $params[':busqueda'] = '%' . trim((string)$filtros['q']) . '%';
        }

        $sql .= " ORDER BY p.ID_PRODUCTO DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductoPorId(int $idProducto): ?array
    {
        $sql = "SELECT
                    p.ID_PRODUCTO,
                    p.NOMBRE,
                    p.DESCRIPCION,
                    p.PRECIO,
                    c.NOMBRE AS CATEGORIA,
                    m.NOMBRE AS MARCA,
                    img.URL_IMAGE
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                WHERE p.ID_PRODUCTO = :idProducto
                  AND p.ID_ESTADO = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idProducto' => $idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $producto ?: null;
    }

    public function obtenerCategoriasConProductos(): array
    {
        $sql = "SELECT DISTINCT c.ID_CATEGORIA, c.NOMBRE
                FROM CATEGORIA_TB c
                JOIN PRODUCTO_TB p ON p.ID_CATEGORIA = c.ID_CATEGORIA
                WHERE c.ID_ESTADO = 1 AND p.ID_ESTADO = 1
                ORDER BY c.NOMBRE ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMarcasConProductos(): array
    {
        $sql = "SELECT DISTINCT m.ID_MARCA, m.NOMBRE
                FROM MARCA_TB m
                JOIN PRODUCTO_TB p ON p.ID_MARCA = m.ID_MARCA
                WHERE m.ID_ESTADO = 1 AND p.ID_ESTADO = 1
                ORDER BY m.NOMBRE ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosPorIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $ids = array_values(array_map('intval', $ids));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT
                    p.ID_PRODUCTO,
                    p.NOMBRE,
                    p.PRECIO,
                    img.URL_IMAGE
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                WHERE p.ID_ESTADO = 1
                  AND p.ID_PRODUCTO IN ($placeholders)
                ORDER BY p.NOMBRE ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
