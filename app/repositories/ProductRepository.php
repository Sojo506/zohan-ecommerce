<?php

require_once __DIR__ . '/../models/ProductModel.php';

class ProductRepository
{
    private ProductModel $model;

    public function __construct(?ProductModel $model = null)
    {
        $this->model = $model ?? new ProductModel();
    }

    /* =========================
       ADMIN PRODUCTOS
    ========================= */

    public function getCategories()
    {
        return $this->model->obtenerCategorias();
    }

    public function getBrands()
    {
        return $this->model->obtenerMarcas();
    }

    public function all()
    {
        return $this->model->obtenerTodosProductos();
    }

    public function create(array $data)
    {
        return $this->model->crearProducto($data);
    }

    public function find(int $id)
    {
        return $this->model->obtenerProductoPorId($id);
    }

    public function update(int $id, array $data)
    {
        return $this->model->actualizarProducto($id, $data);
    }

    public function delete(int $id)
    {
        return $this->model->eliminarProducto($id);
    }

    public function addImage(int $productId, string $url)
    {
        return $this->model->agregarImagenProducto($productId, $url);
    }

    public function getImages(int $productId)
    {
        return $this->model->obtenerImagenesProducto($productId);
    }

    public function deleteImage(int $imageId)
    {
        return $this->model->eliminarImagenProducto($imageId);
    }

    /* =========================
       CATALOGO TIENDA
    ========================= */

    public function fetchCatalog(array $filtros): array
    {
        $productos = $this->model->obtenerProductos($filtros);
        $categorias = $this->model->obtenerCategoriasConProductos();
        $marcas = $this->model->obtenerMarcasConProductos();

        return [
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
        ];
    }

    public function fetchProduct(int $idProducto): ?array
    {
        return $this->model->obtenerProductoPorId($idProducto);
    }

    public function fetchProductImages(int $idProducto): array
    {
        return $this->model->obtenerImagenesProducto($idProducto);
    }

    public function fetchProductStock(int $idProducto): int
    {
        return $this->model->obtenerStockProducto($idProducto);
    }

    /* =========================
       CARRITO
    ========================= */

    public function sanitizeCart($cart): array
    {
        if (!is_array($cart)) {
            return [];
        }

        // Normaliza el carrito para quedarse solo con pares producto/cantidad válidos.
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
        $ids = array_keys($cart);
        $productos = $this->model->obtenerProductosPorIds($ids);

        // Reindexa por ID para validar existencia y consultar cada producto en O(1).
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

            $subtotal = $cantidadInt * (float)$producto['PRECIO'];
            $total += $subtotal;

            $items[] = [
                'producto' => $producto,
                'cantidad' => $cantidadInt,
                'subtotal' => $subtotal,
            ];

            // Devuelve también el carrito depurado para actualizar la sesión con IDs vigentes.
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
        $cart = $this->sanitizeCart($cart);

        $ids = array_keys($cart);

        if (!empty($ids)) {

            $productos = $this->model->obtenerProductosPorIds($ids);

            $validos = [];

            foreach ($productos as $producto) {
                $validos[(int)$producto['ID_PRODUCTO']] = true;
            }

            // El conteo ignora productos eliminados o inválidos aunque sigan en sesión.
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
        return $this->sanitizeCart(
            $this->model->obtenerCarritoCuenta($idCuenta)
        );
    }

    public function saveCartForAccount(int $idCuenta, array $cart): void
    {
        $this->model->guardarCarritoCuenta(
            $idCuenta,
            $this->sanitizeCart($cart)
        );
    }
}
