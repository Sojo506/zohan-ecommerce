<?php

class ProductModel
{
    private $db;
    private ?bool $productoTieneSku = null;
    private ?bool $productoTieneStockMinimo = null;

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

    private function subconsultaPromocion(): string
    {
        return "(
            SELECT
                pp.ID_PRODUCTO,
                pr.ID_PROMOCION,
                pr.NOMBRE AS PROMO_NOMBRE,
                pr.PORCENTAJE
            FROM PROMOCION_PRODUCTO_TB pp
            JOIN PROMOCION_TB pr ON pr.ID_PROMOCION = pp.ID_PROMOCION
            WHERE pp.ID_ESTADO = 1
              AND pr.ID_ESTADO = 1
              AND (pr.FECHA_INICIO IS NULL OR pr.FECHA_INICIO <= CURDATE())
              AND (pr.FECHA_FIN IS NULL OR pr.FECHA_FIN >= CURDATE())
        )";
    }

    private function normalizarCategoriaFiltro(string $categoria): string
    {
        $categoria = trim(strtolower($categoria));

        $map = [
            'components' => 'componentes',
            'componentes' => 'componentes',
            'accessories' => 'accesorios',
            'accesorios' => 'accesorios',
            'gaming' => 'gaming',
            'laptops' => 'laptops',
        ];

        return $map[$categoria] ?? $categoria;
    }

    private function textoClasificacionExpr(): string
    {
        return "LOWER(CONCAT_WS(' ', c.NOMBRE, p.NOMBRE, p.DESCRIPCION))";
    }

    private function productoTieneSku(): bool
    {
        if ($this->productoTieneSku !== null) {
            return $this->productoTieneSku;
        }

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM PRODUCTO_TB LIKE 'SKU'");
            $this->productoTieneSku = (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->productoTieneSku = false;
        }

        return $this->productoTieneSku;
    }

    private function skuSelectExpr(): string
    {
        return $this->productoTieneSku() ? "p.SKU AS SKU" : "NULL AS SKU";
    }

    private function productoTieneStockMinimo(): bool
    {
        if ($this->productoTieneStockMinimo !== null) {
            return $this->productoTieneStockMinimo;
        }

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM PRODUCTO_TB LIKE 'STOCK_MINIMO'");
            $this->productoTieneStockMinimo = (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->productoTieneStockMinimo = false;
        }

        return $this->productoTieneStockMinimo;
    }

    private function stockMinimoSelectExpr(): string
    {
        return $this->productoTieneStockMinimo() ? "p.STOCK_MINIMO AS STOCK_MINIMO" : "0 AS STOCK_MINIMO";
    }

    private function categoriaCanonicaExpr(): string
    {
        $texto = $this->textoClasificacionExpr();

        return "CASE
                    WHEN LOWER(c.NOMBRE) = 'laptops' THEN 'laptops'
                    WHEN LOWER(c.NOMBRE) = 'componentes' THEN 'componentes'
                    WHEN LOWER(c.NOMBRE) = 'gaming' THEN 'gaming'
                    WHEN LOWER(c.NOMBRE) = 'accesorios' THEN 'accesorios'

                    WHEN LOWER(c.NOMBRE) IN ('placa madre', 'placas madre', 'tarjeta grafica', 'tarjetas graficas', 'almacenamiento', 'fuente de poder', 'fuentes de poder', 'refrigeracion')
                    THEN 'componentes'

                    WHEN LOWER(c.NOMBRE) IN ('perifericos', 'periféricos')
                      AND (
                          $texto LIKE '%audio%'
                          OR $texto LIKE '%audif%'
                          OR $texto LIKE '%auricular%'
                          OR $texto LIKE '%headset%'
                      )
                    THEN 'accesorios'

                    WHEN LOWER(c.NOMBRE) IN ('perifericos', 'periféricos')
                    THEN 'gaming'

                    WHEN $texto LIKE '%legion go%'
                      OR $texto LIKE '%playstation%'
                      OR $texto LIKE '%ps5%'
                      OR $texto LIKE '%xbox%'
                      OR $texto LIKE '%nintendo%'
                      OR $texto LIKE '%consola portatil%'
                    THEN 'gaming'

                    WHEN $texto LIKE '%mochila%'
                      OR $texto LIKE '%funda%'
                      OR $texto LIKE '%airpods%'
                      OR $texto LIKE '%beats%'
                      OR $texto LIKE '%audif%'
                      OR $texto LIKE '%auricular%'
                      OR $texto LIKE '%headset%'
                      OR $texto LIKE '%audio%'
                      OR $texto LIKE '%cable%'
                      OR $texto LIKE '%hub%'
                      OR $texto LIKE '%usb-c%'
                      OR $texto LIKE '%cargador%'
                    THEN 'accesorios'

                    WHEN $texto LIKE '%caddy%'
                      OR $texto LIKE '%placa madre%'
                      OR $texto LIKE '%motherboard%'
                      OR $texto LIKE '%tarjeta graf%'
                      OR $texto LIKE '%gpu%'
                      OR $texto LIKE '%ram%'
                      OR $texto LIKE '%ssd%'
                      OR $texto LIKE '%hdd%'
                      OR $texto LIKE '%psu%'
                      OR $texto LIKE '%nvme%'
                      OR $texto LIKE '%m.2%'
                      OR $texto LIKE '%disco duro%'
                      OR $texto LIKE '%fuente de poder%'
                      OR $texto LIKE '%refrigeracion%'
                      OR $texto LIKE '%cooler%'
                    THEN 'componentes'

                    WHEN $texto LIKE '%laptop%'
                      OR $texto LIKE '%notebook%'
                      OR $texto LIKE '%macbook%'
                    THEN 'laptops'

                    ELSE REPLACE(LOWER(c.NOMBRE), ' ', '')
                END";
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
                    COALESCE(inv.STOCK, 0) AS STOCK,
                    promo.PORCENTAJE AS DESCUENTO,
                    promo.PROMO_NOMBRE
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1";

        $params = [];

        if (!empty($filtros['categoria'])) {
            $categoria = $this->normalizarCategoriaFiltro((string)$filtros['categoria']);

            if (in_array($categoria, ['laptops', 'componentes', 'gaming', 'accesorios'], true)) {
                $sql .= " AND " . $this->categoriaCanonicaExpr() . " = :categoriaCanonica";
                $params[':categoriaCanonica'] = $categoria;
            } else {
                $sql .= " AND (
                            LOWER(c.NOMBRE) = :categoriaExacta
                            OR REPLACE(LOWER(c.NOMBRE), ' ', '') = :categoriaLimpia
                          )";
                $params[':categoriaExacta'] = strtolower($categoria);
                $params[':categoriaLimpia'] = str_replace(' ', '', strtolower($categoria));
            }
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

        if (!empty($filtros['promo'])) {
            $sql .= " AND promo.PORCENTAJE IS NOT NULL";
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
                    COALESCE(inv.STOCK, 0) AS STOCK,
                    promo.PORCENTAJE AS DESCUENTO,
                    promo.PROMO_NOMBRE
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
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
                " . $this->skuSelectExpr() . ",
                p.NOMBRE,
                p.DESCRIPCION,
                p.PRECIO,
                " . $this->stockMinimoSelectExpr() . ",
                p.ID_CATEGORIA,
                p.ID_MARCA,
                c.NOMBRE AS CATEGORIA,
                m.NOMBRE AS MARCA,
                img.URL_IMAGE,
                COALESCE(inv.STOCK, 0) AS STOCK,
                promo.PORCENTAJE AS DESCUENTO,
                promo.PROMO_NOMBRE
            FROM PRODUCTO_TB p
            JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
            JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
            LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
            WHERE p.ID_PRODUCTO = :idProducto
              AND p.ID_ESTADO = 1
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([':idProducto' => $idProducto]);

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    return $producto ?: null;
}

public function obtenerProductosSimilares(string $categoria, int $idProducto, int $limite = 6): array
{
    $categoria = trim($categoria);
    if ($categoria === '') {
        return [];
    }

    $sql = "SELECT
                p.ID_PRODUCTO,
                p.NOMBRE,
                p.DESCRIPCION,
                p.PRECIO,
                c.NOMBRE AS CATEGORIA,
                m.NOMBRE AS MARCA,
                img.URL_IMAGE,
                COALESCE(inv.STOCK, 0) AS STOCK,
                promo.PORCENTAJE AS DESCUENTO,
                promo.PROMO_NOMBRE
            FROM PRODUCTO_TB p
            JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
            JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
            LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
            WHERE p.ID_ESTADO = 1
              AND p.ID_PRODUCTO <> :idProducto
              AND (
                    LOWER(c.NOMBRE) = :categoriaExacta
                    OR REPLACE(LOWER(c.NOMBRE), ' ', '') = :categoriaLimpia
              )
            ORDER BY p.ID_PRODUCTO DESC
            LIMIT :limite";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
    $stmt->bindValue(':categoriaExacta', strtolower($categoria));
    $stmt->bindValue(':categoriaLimpia', str_replace(' ', '', strtolower($categoria)));
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function obtenerImagenesProducto(int $idProducto): array
    {
        $sql = "SELECT ID_IMAGEN, URL_IMAGE
                FROM PRODUCTO_IMAGE_TB
                WHERE ID_PRODUCTO = :idProducto
                  AND ID_ESTADO = 1
                ORDER BY ID_IMAGEN ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idProducto' => $idProducto]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return [
            ['ID_CATEGORIA' => 1, 'NOMBRE' => 'Laptops'],
            ['ID_CATEGORIA' => 2, 'NOMBRE' => 'Componentes'],
            ['ID_CATEGORIA' => 3, 'NOMBRE' => 'Gaming'],
            ['ID_CATEGORIA' => 4, 'NOMBRE' => 'Accesorios'],
        ];
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
                    COALESCE(inv.STOCK, 0) AS STOCK,
                    promo.PORCENTAJE AS DESCUENTO,
                    promo.PROMO_NOMBRE
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1
                  AND p.ID_PRODUCTO IN ($placeholders)
                ORDER BY p.NOMBRE ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosEnPromocion(int $limite = 8): array
    {
        $sql = "SELECT
                    p.ID_PRODUCTO,
                    p.NOMBRE,
                    p.DESCRIPCION,
                    p.PRECIO,
                    c.NOMBRE AS CATEGORIA,
                    m.NOMBRE AS MARCA,
                    img.URL_IMAGE,
                    COALESCE(inv.STOCK, 0) AS STOCK,
                    promo.PORCENTAJE AS DESCUENTO,
                    promo.PROMO_NOMBRE
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN INVENTARIO_TB inv ON inv.ID_PRODUCTO = p.ID_PRODUCTO AND inv.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1
                  AND promo.PORCENTAJE IS NOT NULL
                ORDER BY promo.PORCENTAJE DESC, p.ID_PRODUCTO DESC
                LIMIT :limite";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
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

    public function obtenerTodosProductos(): array
    {
        $sql = "SELECT 
                p.ID_PRODUCTO,
                " . $this->skuSelectExpr() . ",
                p.NOMBRE,
                p.DESCRIPCION,
                p.PRECIO,
                " . $this->stockMinimoSelectExpr() . ",
                c.NOMBRE AS CATEGORIA,
                m.NOMBRE AS MARCA,
                GROUP_CONCAT(CONCAT(pi.ID_IMAGEN,'::',pi.URL_IMAGE) SEPARATOR '||') AS IMAGENES
            FROM PRODUCTO_TB p
            JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
            JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
            LEFT JOIN PRODUCTO_IMAGE_TB pi 
                ON pi.ID_PRODUCTO = p.ID_PRODUCTO
                AND pi.ID_ESTADO = 1
            WHERE p.ID_ESTADO = 1
            GROUP BY p.ID_PRODUCTO
            ORDER BY p.ID_PRODUCTO DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCategorias(): array
    {
        $sql = "SELECT ID_CATEGORIA, NOMBRE
            FROM CATEGORIA_TB
            WHERE ID_ESTADO = 1
            ORDER BY NOMBRE";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMarcas(): array
    {
        $sql = "SELECT ID_MARCA, NOMBRE
            FROM MARCA_TB
            WHERE ID_ESTADO = 1
            ORDER BY NOMBRE";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearProducto(array $data)
    {
        $this->db->beginTransaction();

        try {

            $sql = "INSERT INTO PRODUCTO_TB
        (SKU,NOMBRE,DESCRIPCION,PRECIO,STOCK_MINIMO,ID_CATEGORIA,ID_MARCA,ID_ESTADO)
        VALUES
        (:sku,:nombre,:desc,:precio,:stock_min,:categoria,:marca,1)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':sku' => $data['sku'],
                ':nombre' => $data['nombre'],
                ':desc' => $data['descripcion'],
                ':precio' => $data['precio'],
                ':stock_min' => $data['stock_min'],
                ':categoria' => $data['categoria'],
                ':marca' => $data['marca']
            ]);

            $productId = $this->db->lastInsertId();

            $sqlInventory = "INSERT INTO INVENTARIO_TB
        (ID_PRODUCTO,STOCK,ID_ESTADO)
        VALUES
        (:producto,0,1)";

            $stmtInv = $this->db->prepare($sqlInventory);

            $stmtInv->execute([
                ':producto' => $productId
            ]);

            $this->db->commit();

            return $productId;
        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;
        }
    }

    public function actualizarProducto(int $id, array $data)
    {
        $sql = "UPDATE PRODUCTO_TB SET
            SKU = :sku,
            NOMBRE = :nombre,
            DESCRIPCION = :desc,
            PRECIO = :precio,
            STOCK_MINIMO = :stock_min,
            ID_CATEGORIA = :categoria,
            ID_MARCA = :marca
            WHERE ID_PRODUCTO = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':sku' => $data['sku'],
            ':nombre' => $data['nombre'],
            ':desc' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock_min' => $data['stock_min'],
            ':categoria' => $data['categoria'],
            ':marca' => $data['marca'],
            ':id' => $id
        ]);
    }

    public function eliminarProducto(int $id)
    {
        $sql = "UPDATE PRODUCTO_TB
            SET ID_ESTADO = 2
            WHERE ID_PRODUCTO = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public function agregarImagenProducto(int $productId, string $url)
    {
        $sql = "INSERT INTO PRODUCTO_IMAGE_TB
            (ID_PRODUCTO,URL_IMAGE,ID_ESTADO)
            VALUES(:product,:url,1)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':product' => $productId,
            ':url' => $url
        ]);
    }

    public function eliminarImagenProducto(int $imageId)
    {
        $sql = "UPDATE PRODUCTO_IMAGE_TB
            SET ID_ESTADO = 2
            WHERE ID_IMAGEN = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $imageId]);
    }
}


