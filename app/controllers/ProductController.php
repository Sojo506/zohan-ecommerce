<?php

require_once __DIR__ . '/../repositories/ProductRepository.php';
require_once __DIR__ . '/../repositories/CartRepository.php';

class ProductController extends Controller
{
    private ProductRepository $repository;
    private CartRepository $cartRepository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
        $this->cartRepository = new CartRepository($this->repository);

        // Mantiene la sesión del carrito alineada con el estado persistido del usuario.
        $this->cartRepository->syncSessionCart();
    }

    public function index()
    {
        $categoria = trim((string)($_GET['category'] ?? ''));
        if ($categoria !== '') {
            // Traduce slugs de la URL al nombre de categoría que entiende el modelo.
            $map = [
                'components' => 'componentes',
                'accessories' => 'accesorios',
            ];
            $key = strtolower($categoria);
            if (isset($map[$key])) {
                $categoria = $map[$key];
            }
        }

        $filtros = [
            'categoria' => $categoria,
            'marca' => trim($_GET['brand'] ?? ''),
            'q' => trim($_GET['q'] ?? ''),
            'promo' => (int)($_GET['promo'] ?? 0)
        ];

        $data = $this->repository->fetchCatalog($filtros);

        $this->view('products/productos', [
            'productos' => $data['productos'],
            'categorias' => $data['categorias'],
            'marcas' => $data['marcas'],
            'filtros' => $filtros,
            'cartCount' => $this->cartRepository->countCurrentCart()
        ]);
    }

    public function show()
    {
        $idProducto = (int)($_GET['id'] ?? 0);

        if ($idProducto <= 0) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/tienda'));
            exit;
        }

        $producto = $this->repository->fetchProduct($idProducto);

        if (!$producto) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/tienda'));
            exit;
        }

        $imagenes = $this->repository->fetchProductImages($idProducto);

        // Normaliza la lista porque algunas consultas devuelven filas completas y otras solo URLs.
        $imagenes = array_values(array_filter(array_map(static function ($imagen) {
            if (is_array($imagen)) {
                return trim((string)($imagen['URL_IMAGE'] ?? ''));
            }

            return trim((string)$imagen);
        }, $imagenes), static function ($imagenUrl) {
            return $imagenUrl !== '';
        }));

        if (!empty($imagenes)) {
            // La primera imagen funciona como portada principal del detalle.
            $producto['URL_IMAGE'] = $imagenes[0];
        }

        $existencias = $this->repository->fetchProductStock($idProducto);

        $similares = [];
        if (!empty($producto['CATEGORIA'])) {
            $similares = $this->repository->fetchSimilarProducts(
                (string)$producto['CATEGORIA'],
                $idProducto,
                6
            );
        }

        $this->view('products/producto', [
            'producto' => $producto,
            'imagenes' => $imagenes,
            'existencias' => $existencias,
            'similares' => $similares,
            'cartCount' => $this->cartRepository->countCurrentCart()
        ]);
    }

    public function cart()
    {
        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);
        $summary = $this->repository->buildCartSummary($cart);
        $this->setCart($summary['cart']);

        $this->view('cart/index', [
            'items' => $summary['items'],
            'total' => $summary['total'],
            'cartCount' => $this->repository->countCart($summary['cart'])
        ]);
    }

    public function addToCart()
    {
        $idProducto = (int)($_POST['id_producto'] ?? 0);
        $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));

        $producto = $this->repository->fetchProduct($idProducto);
        if (!$producto) {
            $_SESSION['flash_error'] = 'No se pudo agregar: producto invalido.';
            header('Location: ' . App::url('/tienda'));
            exit;
        }

        $stock = $this->repository->fetchProductStock($idProducto);
        if ($stock <= 0) {
            $_SESSION['flash_error'] = 'Producto sin existencias.';
            $this->redirectBack();
        }

        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);
        $actual = (int)($cart[$idProducto] ?? 0);
        $maxAgregar = $stock - $actual;

        if ($maxAgregar <= 0) {
            $_SESSION['flash_error'] = 'No hay mas existencias disponibles.';
            $this->redirectBack();
        }

        $solicitado = $cantidad;
        $cantidad = min($cantidad, $maxAgregar);
        $cart[$idProducto] = $actual + $cantidad;
        $this->setCart($cart);

        if ($cantidad < $solicitado) {
            $_SESSION['flash_success'] = 'Cantidad ajustada a existencias.';
        } else {
            $_SESSION['flash_success'] = 'Producto agregado al carrito.';
        }

        $this->redirectBack();
    }

    public function updateCart()
    {
        $idProducto = (int)($_POST['id_producto'] ?? 0);

        if ($idProducto <= 0) {
            $_SESSION['flash_error'] = 'Producto invalido.';
            header('Location: ' . App::url('/cart'));
            exit;
        }

        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);

        if (!isset($cart[$idProducto])) {
            $_SESSION['flash_error'] = 'El producto no esta en el carrito.';
            header('Location: ' . App::url('/cart'));
            exit;
        }

        $stock = $this->repository->fetchProductStock($idProducto);
        if ($stock <= 0) {
            unset($cart[$idProducto]);
            $_SESSION['flash_error'] = 'Producto sin existencias.';
            $this->setCart($cart);
            header('Location: ' . App::url('/cart'));
            exit;
        }

        $actual = (int)$cart[$idProducto];
        $accion = trim((string)($_POST['accion'] ?? ''));
        $ajustada = false;

        if (in_array($accion, ['sumar', 'increase', 'increment'], true)) {
            $nuevaCantidad = $actual + 1;
        } elseif (in_array($accion, ['restar', 'decrease', 'decrement'], true)) {
            $nuevaCantidad = $actual - 1;
        } else {
            $nuevaCantidad = max(1, (int)($_POST['cantidad'] ?? $actual));
        }

        if ($nuevaCantidad <= 0) {
            unset($cart[$idProducto]);
            $_SESSION['flash_success'] = 'Producto eliminado del carrito.';
            $this->setCart($cart);
            header('Location: ' . App::url('/cart'));
            exit;
        }

        if ($nuevaCantidad > $stock) {
            $nuevaCantidad = $stock;
            $ajustada = true;
        }

        $cart[$idProducto] = $nuevaCantidad;
        $this->setCart($cart);

        if ($ajustada) {
            $_SESSION['flash_success'] = 'Cantidad ajustada a existencias.';
        } else {
            $_SESSION['flash_success'] = 'Carrito actualizado.';
        }

        header('Location: ' . App::url('/cart'));
        exit;
    }

    private function redirectBack(): void
    {
        $back = $_SERVER['HTTP_REFERER'] ?? App::url('/tienda');
        header('Location: ' . $back);
        exit;
    }
}