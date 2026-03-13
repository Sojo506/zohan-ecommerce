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
        $this->cartRepository->syncSessionCart();
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
            header('Location: ' . App::url('/products'));
            exit;
        }

        $producto = $this->repository->fetchProduct($idProducto);

        if (!$producto) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/products'));
            exit;
        }

        $imagenes = $this->repository->fetchProductImages($idProducto);
        $imagenes = array_values(array_filter(array_map(static function ($imagen) {
            if (is_array($imagen)) {
                return trim((string)($imagen['URL_IMAGE'] ?? ''));
            }

            return trim((string)$imagen);
        }, $imagenes), static function ($imagenUrl) {
            return $imagenUrl !== '';
        }));

        if (!empty($imagenes)) {
            $producto['URL_IMAGE'] = $imagenes[0];
        }

        $existencias = $this->repository->fetchProductStock($idProducto);

        $this->view('products/producto', [
            'producto' => $producto,
            'imagenes' => $imagenes,
            'existencias' => $existencias,
            'cartCount' => $this->cartRepository->countCurrentCart()
        ]);
    }
}
