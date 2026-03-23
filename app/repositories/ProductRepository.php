<?php
require_once __DIR__ . '/../models/ProductModel.php';

class ProductRepository
{
    private ProductModel $model;

    public function __construct(?ProductModel $model = null)
    {
        $this->model = $model ?? new ProductModel();
    }

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

    public function fetchSimilarProducts(string $categoria, int $idProducto, int $limite = 6): array
    {
        return $this->model->obtenerProductosSimilares($categoria, $idProducto, $limite);
    }


    public function sanitizeCart($cart): array
    {
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
        $ids = array_keys($cart);
        $productos = $this->model->obtenerProductosPorIds($ids);

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
        $cart = $this->sanitizeCart($cart);
        $ids = array_keys($cart);

        if (!empty($ids)) {
            $productos = $this->model->obtenerProductosPorIds($ids);
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
        return $this->sanitizeCart($this->model->obtenerCarritoCuenta($idCuenta));
    }

    public function saveCartForAccount(int $idCuenta, array $cart): void
    {
        $this->model->guardarCarritoCuenta($idCuenta, $this->sanitizeCart($cart));
    }
}
?>






