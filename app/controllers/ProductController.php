<?php
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController extends Controller
{
    private ProductModel ;

    public function __construct()
    {
        ->productModel = new ProductModel();
        ->sanitizeCart();
    }

    public function index()
    {
         = [
            'categoria' => trim(['category'] ?? ''),
            'marca' => trim(['brand'] ?? ''),
            'q' => trim(['q'] ?? '')
        ];

         = ->productModel->obtenerProductos();
         = ->productModel->obtenerCategoriasConProductos();
         = ->productModel->obtenerMarcasConProductos();

        ->view('products/productos', [
            'productos' => ,
            'categorias' => ,
            'marcas' => ,
            'filtros' => ,
            'cartCount' => ->obtenerCantidadCarrito()
        ]);
    }

    public function show()
    {
         = (int)(['id'] ?? 0);

        if ( <= 0) {
            ['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/products'));
            exit;
        }

         = ->productModel->obtenerProductoPorId();

        if (!) {
            ['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . App::url('/products'));
            exit;
        }

        ->view('products/producto', [
            'producto' => ,
            'cartCount' => ->obtenerCantidadCarrito()
        ]);
    }

    public function cart()
    {
        ->sanitizeCart();
         = ['cart'];
         = array_keys();
         = ->productModel->obtenerProductosPorIds();

         = [];
         = 0;

        // Indexar productos para validar IDs existentes en el carrito
         = [];
        foreach ( as ) {
            [(int)['ID_PRODUCTO']] = ;
        }

        // Reconstruir el carrito solo con productos válidos y cantidades positivas
        foreach ( as  => ) {
             = (int);
             = (int);

            if ( <= 0 || !isset([])) {
                unset(['cart'][]);
                continue;
            }

             = [];
             =  * (float)['PRECIO'];
             += ;

            [] = [
                'producto' => ,
                'cantidad' => ,
                'subtotal' => 
            ];
        }

        ->view('cart/index', [
            'items' => ,
            'total' => ,
            'cartCount' => ->obtenerCantidadCarrito()
        ]);
    }

    public function addToCart()
    {
         = (int)(['id_producto'] ?? 0);
         = max(1, (int)(['cantidad'] ?? 1));

         = ->productModel->obtenerProductoPorId();
        if (!) {
            ['flash_error'] = 'No se pudo agregar: producto inválido.';
            header('Location: ' . App::url('/products'));
            exit;
        }

         = (int)(['cart'][] ?? 0);
        ['cart'][] =  + ;

        ['flash_success'] = 'Producto agregado al carrito.';
        ->redirectBack();
    }

    public function updateCart()
    {
         = (int)(['id_producto'] ?? 0);

        if ( <= 0) {
            ['flash_error'] = 'Producto inválido.';
            header('Location: ' . App::url('/cart'));
            exit;
        }

        if (!isset(['cart'][])) {
            ['flash_error'] = 'El producto no está en el carrito.';
            header('Location: ' . App::url('/cart'));
            exit;
        }

         = (int)(['cart'][] ?? 0);
         = trim((string)(['accion'] ?? ''));

        if ( === 'sumar') {
             =  + 1;
        } elseif ( === 'restar') {
             =  - 1;
        } else {
             = (int)(['cantidad'] ?? );
        }

        if ( <= 0) {
            unset(['cart'][]);
            ['flash_success'] = 'Producto eliminado del carrito.';
        } else {
            ['cart'][] = ;
            ['flash_success'] = 'Cantidad actualizada en el carrito.';
        }

        header('Location: ' . App::url('/cart'));
        exit;
    }

    public function removeFromCart()
    {
         = (int)(['id_producto'] ?? 0);

        if (isset(['cart'][])) {
            unset(['cart'][]);
            ['flash_success'] = 'Producto eliminado del carrito.';
        }

        header('Location: ' . App::url('/cart'));
        exit;
    }

    public function clearCart()
    {
        ['cart'] = [];
        ['flash_success'] = 'Carrito vaciado.';
        header('Location: ' . App::url('/cart'));
        exit;
    }

    private function sanitizeCart(): void
    {
        if (!isset(['cart']) || !is_array(['cart'])) {
            ['cart'] = [];
            return;
        }

         = [];
        foreach (['cart'] as  => ) {
             = (int);
             = (int);
            if ( > 0 &&  > 0) {
                [] = ;
            }
        }

        ['cart'] = ;
    }

    private function obtenerCantidadCarrito(): int
    {
        ->sanitizeCart();

         = array_keys(['cart']);
        if (!empty()) {
             = ->productModel->obtenerProductosPorIds();
             = [];
            foreach ( as ) {
                [(int)['ID_PRODUCTO']] = true;
            }

            foreach (array_keys(['cart']) as ) {
                 = (int);
                if (!isset([])) {
                    unset(['cart'][]);
                }
            }
        }

        return array_sum(['cart']);
    }

    private function redirectBack(): void
    {
         = ['HTTP_REFERER'] ?? App::url('/products');
        header('Location: ' . );
        exit;
    }
}
?>
