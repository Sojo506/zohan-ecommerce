<?php

// Repositorio principal del catálogo: concentra consultas de tienda, admin y apoyo al carrito.
class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    private function subconsultaImagen(): string
    {
        // Se reutiliza para exponer una sola imagen representativa sin duplicar productos por cada foto.
        return "(
            SELECT ID_PRODUCTO, MIN(URL_IMAGE) AS URL_IMAGE
            FROM PRODUCTO_IMAGE_TB
            WHERE ID_ESTADO = 1
            GROUP BY ID_PRODUCTO
        )";
    }

    private function subconsultaPromocion(): string
    {
        // Devuelve solo promociones vigentes para que el catálogo no tenga que recalcular fechas en PHP.
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

    private function subconsultaResenas(): string
    {
        // Resume promedio y cantidad usando solo comentarios visibles.
        return "(
            SELECT
                c.ID_PRODUCTO,
                COUNT(*) AS TOTAL_COMENTARIOS,
                ROUND(AVG(c.CALIFICACION), 1) AS CALIFICACION_PROMEDIO
            FROM COMENTARIO_TB c
            WHERE c.ID_ESTADO = 1
            GROUP BY c.ID_PRODUCTO
        )";
    }

    public function obtenerProductos(array $filtros = []): array
    {
        // Consulta base del catálogo con joins comunes; luego se van agregando filtros opcionales.
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
                    promo.PROMO_NOMBRE,
                    COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                    COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
                    promo.PROMO_NOMBRE,
                    COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                    COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
                p.SKU,
                p.NOMBRE,
                p.DESCRIPCION,
                p.PRECIO,
                p.STOCK_MINIMO,
                p.ID_CATEGORIA,
                p.ID_MARCA,
                c.NOMBRE AS CATEGORIA,
                m.NOMBRE AS MARCA,
                img.URL_IMAGE,
                COALESCE(inv.STOCK, 0) AS STOCK,
                promo.PORCENTAJE AS DESCUENTO,
                promo.PROMO_NOMBRE,
                COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
            FROM PRODUCTO_TB p
            JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
            JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
            LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
                promo.PROMO_NOMBRE,
                COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
            FROM PRODUCTO_TB p
            JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
            JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
            LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
            LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
                    COALESCE(inv.STOCK, 0) AS STOCK,
                    promo.PORCENTAJE AS DESCUENTO,
                    promo.PROMO_NOMBRE,
                    COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                    COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
                FROM PRODUCTO_TB p
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
                    promo.PROMO_NOMBRE,
                    COALESCE(resenas.CALIFICACION_PROMEDIO, 0) AS CALIFICACION_PROMEDIO,
                    COALESCE(resenas.TOTAL_COMENTARIOS, 0) AS TOTAL_COMENTARIOS
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN " . $this->subconsultaImagen() . " img ON img.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaPromocion() . " promo ON promo.ID_PRODUCTO = p.ID_PRODUCTO
                LEFT JOIN " . $this->subconsultaResenas() . " resenas ON resenas.ID_PRODUCTO = p.ID_PRODUCTO
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
        // La estrategia es "replace all": el detalle persistido queda exactamente igual al carrito en memoria.
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
                p.SKU,
                p.NOMBRE,
                p.DESCRIPCION,
                p.PRECIO,
                p.STOCK_MINIMO,
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

    /* =========================
       API COMPATIBLE CON CONTROLADORES
    ========================= */

    public function getCategories(): array
    {
        return $this->obtenerCategorias();
    }

    public function getBrands(): array
    {
        return $this->obtenerMarcas();
    }

    public function all(): array
    {
        return $this->obtenerTodosProductos();
    }

    public function create(array $data)
    {
        return $this->crearProducto($data);
    }

    public function find(int $id): ?array
    {
        return $this->obtenerProductoPorId($id);
    }

    public function update(int $id, array $data): void
    {
        $this->actualizarProducto($id, $data);
    }

    public function delete(int $id): void
    {
        $this->eliminarProducto($id);
    }

    public function addImage(int $productId, string $url): void
    {
        $this->agregarImagenProducto($productId, $url);
    }

    public function getImages(int $productId): array
    {
        return $this->obtenerImagenesProducto($productId);
    }

    public function deleteImage(int $imageId): void
    {
        $this->eliminarImagenProducto($imageId);
    }

    public function fetchCatalog(array $filtros): array
    {
        // Entrega a la vista todo lo necesario para pintar catálogo y filtros laterales en una sola llamada.
        return [
            'productos' => $this->obtenerProductos($filtros),
            'categorias' => $this->obtenerCategoriasConProductos(),
            'marcas' => $this->obtenerMarcasConProductos(),
        ];
    }

    public function fetchProduct(int $idProducto): ?array
    {
        return $this->obtenerProductoPorId($idProducto);
    }

    public function fetchProductImages(int $idProducto): array
    {
        return $this->obtenerImagenesProducto($idProducto);
    }

    public function fetchProductStock(int $idProducto): int
    {
        return $this->obtenerStockProducto($idProducto);
    }

    public function fetchSimilarProducts(string $categoria, int $idProducto, int $limite = 6): array
    {
        return $this->obtenerProductosSimilares($categoria, $idProducto, $limite);
    }

    public function sanitizeCart($cart): array
    {
        // Normaliza el carrito al formato [idProducto => cantidad] con enteros positivos.
        if (!is_array($cart)) {
            return [];
        }

        $clean = [];

        foreach ($cart as $id => $cantidad) {
            $idInt = (int)$id;
            $cantidadInt = (int)$cantidad;

            if ($idInt > 0 && $cantidadInt > 0) {
                $clean[$idInt] = $cantidadInt;
            }
        }

        return $clean;
    }

    public function buildCartSummary(array $cart): array
    {
        // Solo sobreviven productos vigentes; cualquier ID inválido se elimina del carrito limpio retornado.
        $ids = array_keys($cart);
        $productos = $this->obtenerProductosPorIds($ids);
        $productosIndex = [];

        foreach ($productos as $producto) {
            $productosIndex[(int)$producto['ID_PRODUCTO']] = $producto;
        }

        $items = [];
        $total = 0;
        $clean = [];

        foreach ($cart as $id => $cantidad) {
            $idInt = (int)$id;
            $cantidadInt = (int)$cantidad;

            if ($cantidadInt <= 0 || !isset($productosIndex[$idInt])) {
                continue;
            }

            $producto = $productosIndex[$idInt];
            $precio = (float)$producto['PRECIO'];
            $descuento = (float)($producto['DESCUENTO'] ?? 0);

            // El subtotal se calcula con precio promocional si existe descuento activo.
            if ($descuento > 0) {
                $precio = $precio * (1 - ($descuento / 100));
            }

            $subtotal = $cantidadInt * $precio;
            $total += $subtotal;

            $items[] = [
                'producto' => $producto,
                'cantidad' => $cantidadInt,
                'subtotal' => $subtotal,
            ];

            $clean[$idInt] = $cantidadInt;
        }

        return [
            'items' => $items,
            'total' => $total,
            'cart' => $clean,
        ];
    }

    public function countCart(array $cart): int
    {
        // El contador ignora productos ya inactivos o inexistentes para no inflar el badge del carrito.
        $cart = $this->sanitizeCart($cart);
        $ids = array_keys($cart);

        if (!empty($ids)) {
            $productos = $this->obtenerProductosPorIds($ids);
            $validos = [];

            foreach ($productos as $producto) {
                $validos[(int)$producto['ID_PRODUCTO']] = true;
            }

            foreach (array_keys($cart) as $id) {
                $idInt = (int)$id;

                if (!isset($validos[$idInt])) {
                    unset($cart[$id]);
                }
            }
        }

        return array_sum($cart);
    }

    public function fetchCartForAccount(int $idCuenta): array
    {
        return $this->sanitizeCart($this->obtenerCarritoCuenta($idCuenta));
    }

    public function saveCartForAccount(int $idCuenta, array $cart): void
    {
        $this->guardarCarritoCuenta($idCuenta, $this->sanitizeCart($cart));
    }
}
