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
                    img.URL_IMAGE,
                    COALESCE(inv.STOCK, 0) AS STOCK
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
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
            $sql .= " AND (p.NOMBRE LIKE :busquedaNombre OR p.DESCRIPCION LIKE :busquedaDescripcion)";
            $busqueda = '%' . trim((string)$filtros['q']) . '%';
            $params[':busquedaNombre'] = $busqueda;
            $params[':busquedaDescripcion'] = $busqueda;
        }

        $sql .= " ORDER BY p.ID_PRODUCTO DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosDestacadosSemana(int $limite = 8): array
    {
        $sql = "SELECT
                    p.ID_PRODUCTO,
                    p.NOMBRE,
                    p.DESCRIPCION,
                    p.PRECIO,
                    img.URL_IMAGE,
                    COALESCE(inv.STOCK, 0) AS STOCK
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1
                ORDER BY p.ID_PRODUCTO DESC
                LIMIT :limite";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
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
                    img.URL_IMAGE,
                    COALESCE(inv.STOCK, 0) AS STOCK
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_PRODUCTO = :idProducto
                  AND p.ID_ESTADO = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idProducto' => $idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $producto ?: null;
    }

    public function obtenerImagenesProducto(int $idProducto): array
    {
        $sql = "SELECT URL_IMAGE
                FROM PRODUCTO_IMAGE_TB
                WHERE ID_PRODUCTO = :idProducto
                  AND ID_ESTADO = 1
                ORDER BY ID_IMAGEN ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idProducto' => $idProducto]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $imagenes = [];
        foreach ($rows as $row) {
            if (!empty($row['URL_IMAGE'])) {
                $imagenes[] = $row['URL_IMAGE'];
            }
        }

        return $imagenes;
    }

    public function obtenerStockProducto(int $idProducto): int
    {
        $sql = "SELECT STOCK
                FROM INVENTARIO_TB
                WHERE ID_PRODUCTO = :idProducto
                  AND ID_ESTADO = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idProducto' => $idProducto]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return 0;
        }

        return (int)$row['STOCK'];
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
                    img.URL_IMAGE,
                    COALESCE(inv.STOCK, 0) AS STOCK
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1
                  AND p.ID_PRODUCTO IN ($placeholders)
                ORDER BY p.NOMBRE ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerCarritoIdActivo(int $idCuenta): ?int
    {
        $sql = "SELECT ID_CARRITO
                FROM CARRITO_TB
                WHERE ID_CUENTA = :idCuenta
                  AND ID_ESTADO = 1
                ORDER BY ID_CARRITO DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idCuenta' => $idCuenta]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return (int)$row['ID_CARRITO'];
    }

    private function obtenerOCrearCarritoId(int $idCuenta): int
    {
        $idCarrito = $this->obtenerCarritoIdActivo($idCuenta);
        if ($idCarrito !== null) {
            return $idCarrito;
        }

        $stmt = $this->db->prepare("INSERT INTO CARRITO_TB (ID_CUENTA, ID_ESTADO) VALUES (:idCuenta, 1)");
        $stmt->execute([':idCuenta' => $idCuenta]);

        return (int)$this->db->lastInsertId();
    }

    public function obtenerCarritoCuenta(int $idCuenta): array
    {
        $idCarrito = $this->obtenerCarritoIdActivo($idCuenta);
        if ($idCarrito === null) {
            return [];
        }

        $stmt = $this->db->prepare("SELECT ID_PRODUCTO, CANTIDAD FROM CARRITO_ITEM_TB WHERE ID_CARRITO = :idCarrito");
        $stmt->execute([':idCarrito' => $idCarrito]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cart = [];
        foreach ($rows as $row) {
            $idProducto = (int)$row['ID_PRODUCTO'];
            $cantidad = (int)$row['CANTIDAD'];
            if ($idProducto > 0 && $cantidad > 0) {
                $cart[$idProducto] = $cantidad;
            }
        }

        return $cart;
    }

    public function guardarCarritoCuenta(int $idCuenta, array $cart): void
    {
        $this->db->beginTransaction();

        try {
            $idCarrito = $this->obtenerOCrearCarritoId($idCuenta);

            $stmtDelete = $this->db->prepare("DELETE FROM CARRITO_ITEM_TB WHERE ID_CARRITO = :idCarrito");
            $stmtDelete->execute([':idCarrito' => $idCarrito]);

            if (!empty($cart)) {
                $stmtInsert = $this->db->prepare(
                    "INSERT INTO CARRITO_ITEM_TB (ID_CARRITO, ID_PRODUCTO, CANTIDAD)
                     VALUES (:idCarrito, :idProducto, :cantidad)"
                );

                foreach ($cart as $idProducto => $cantidad) {
                    $stmtInsert->execute([
                        ':idCarrito' => $idCarrito,
                        ':idProducto' => (int)$idProducto,
                        ':cantidad' => (int)$cantidad,
                    ]);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
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









