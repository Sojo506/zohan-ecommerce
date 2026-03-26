<?php

require_once __DIR__ . '/../models/CartModel.php';

class CartController extends Controller
{
    private CartModel $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();

        // Sincroniza el carrito persistido con la sesión antes de atender cualquier acción.
        $this->cartModel->syncSessionCart();
    }

    public function index()
    {
        // El resumen devuelve solo productos válidos y recalcula subtotales y total general.
        $summary = $this->cartModel->buildSummary();

        $this->view('cart/index', [
            'items' => $summary['items'],
            'total' => $summary['total'],
            'cartCount' => $this->cartModel->countCurrentCart()
        ]);
    }

    public function add()
    {
        $result = $this->cartModel->addProduct(
            (int)($_POST['id_producto'] ?? 0),
            (int)($_POST['cantidad'] ?? 1)
        );

        $this->flash($result);
        $this->redirect($_SERVER['HTTP_REFERER'] ?? App::url('/products'));
    }

    public function update()
    {
        $result = $this->cartModel->updateProduct(
            (int)($_POST['id_producto'] ?? 0),
            trim((string)($_POST['accion'] ?? '')),
            isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null
        );

        $this->flash($result);
        $this->redirect(App::url('/cart'));
    }

    public function remove()
    {
        $result = $this->cartModel->removeProduct((int)($_POST['id_producto'] ?? 0));
        $this->flash($result);
        $this->redirect(App::url('/cart'));
    }

    public function clear()
    {
        $this->cartModel->clear();
        $_SESSION['flash_success'] = 'Carrito vaciado.';
        $this->redirect(App::url('/cart'));
    }

    private function flash(array $result): void
    {
        // Unifica el mensaje que la vista leerá después de cada operación del carrito.
        if (($result['success'] ?? false) === true) {
            $_SESSION['flash_success'] = $result['message'] ?? 'Operacion completada.';
            return;
        }

        $_SESSION['flash_error'] = $result['message'] ?? 'Ocurrio un error.';
    }

    private function redirect(string $url): void
    {
        // Centraliza la salida para que todos los flujos terminen con un redirect limpio.
        header('Location: ' . $url);
        exit;
    }
}
