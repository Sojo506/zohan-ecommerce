<?php
require_once __DIR__ . '/../repositories/ProductRepository.php';

class ProductController extends Controller
{
    private ProductRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);
        if (empty($cart) && isset($_SESSION['user']['id_cuenta'])) {
            $cart = $this->repository->fetchCartForAccount((int)$_SESSION['user']['id_cuenta']);
        }
        $this->setCart($cart, false);
    }

    public function index()
    {
        $categoria = trim((string)($_GET['category'] ?? ''));
        if ($categoria !== '') {
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
            'q' => trim($_GET['q'] ?? '')
        ];

        $data = $this->repository->fetchCatalog($filtros);
        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);
        $this->setCart($cart);

        $this->view('products/productos', [
            'productos' => $data['productos'],
            'categorias' => $data['categorias'],
            'marcas' => $data['marcas'],
            'filtros' => $filtros,
            'cartCount' => $this->repository->countCart($cart)
        ]);
    }

    public function show()
    {
        $idProducto = (int)($_GET['id'] ?? 0);

        if ($idProducto <= 0) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/products'));
            exit;
        }

        $producto = $this->repository->fetchProduct($idProducto);

        if (!$producto) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/products'));
            exit;
        }

        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);
        $this->setCart($cart);
        $imagenes = $this->repository->fetchProductImages($idProducto);
        $existencias = $this->repository->fetchProductStock($idProducto);

        $this->view('products/producto', [
            'producto' => $producto,
            'imagenes' => $imagenes,
            'existencias' => $existencias,
            'cartCount' => $this->repository->countCart($cart)
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
            header('Location: ' . App::url('/products'));
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

        if ($accion === 'sumar') {
            if ($actual >= $stock) {
                $_SESSION['flash_error'] = 'No hay mas existencias disponibles.';
                $this->setCart($cart);
                header('Location: ' . App::url('/cart'));
                exit;
            }
            $cantidad = $actual + 1;
        } elseif ($accion === 'restar') {
            $cantidad = $actual - 1;
        } else {
            $cantidad = (int)($_POST['cantidad'] ?? $actual);
            if ($cantidad > $stock) {
                $cantidad = $stock;
                $ajustada = true;
            }
        }

        if ($cantidad <= 0) {
            unset($cart[$idProducto]);
            $_SESSION['flash_success'] = 'Producto eliminado del carrito.';
        } else {
            $cart[$idProducto] = $cantidad;
            if ($ajustada) {
                $_SESSION['flash_success'] = 'Cantidad ajustada a existencias.';
            } else {
                $_SESSION['flash_success'] = 'Cantidad actualizada en el carrito.';
            }
        }

        $this->setCart($cart);
        header('Location: ' . App::url('/cart'));
        exit;
    }

    public function removeFromCart()
    {
        $idProducto = (int)($_POST['id_producto'] ?? 0);
        $cart = $this->repository->sanitizeCart($_SESSION['cart'] ?? []);

        if (isset($cart[$idProducto])) {
            unset($cart[$idProducto]);
            $_SESSION['flash_success'] = 'Producto eliminado del carrito.';
        }

        $this->setCart($cart);
        header('Location: ' . App::url('/cart'));
        exit;
    }

    public function clearCart()
    {
        $this->setCart([]);
        $_SESSION['flash_success'] = 'Carrito vaciado.';
        header('Location: ' . App::url('/cart'));
        exit;
    }

    private function setCart(array $cart, bool $persist = true): void
    {
        $_SESSION['cart'] = $cart;

        if ($persist) {
            $this->persistCartForLoggedUser($cart);
        }
    }

    private function persistCartForLoggedUser(array $cart): void
    {
        if (!isset($_SESSION['user']['id_cuenta'])) {
            return;
        }

        $idCuenta = (int)$_SESSION['user']['id_cuenta'];
        if ($idCuenta <= 0) {
            return;
        }

        try {
            $this->repository->saveCartForAccount($idCuenta, $cart);
        } catch (PDOException $e) {
            // Evitar romper la navegacion si la persistencia falla.
        }
    }

    private function redirectBack(): void
    {
        $back = $_SERVER['HTTP_REFERER'] ?? App::url('/products');
        header('Location: ' . $back);
        exit;
    }
}
?>



