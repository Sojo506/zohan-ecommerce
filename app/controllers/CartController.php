<?php

require_once __DIR__ . '/../repositories/CartRepository.php';

class CartController extends Controller
{
    private CartRepository $repository;

    public function __construct()
    {
        $this->repository = new CartRepository();

        // Sincroniza el carrito persistido con la sesión antes de atender cualquier acción.
        $this->repository->syncSessionCart();
    }

    public function index()
    {
        // El resumen devuelve solo productos válidos y recalcula subtotales y total general.
        $summary = $this->repository->buildSummary();

        $this->view('cart/index', [
            'items' => $summary['items'],
            'total' => $summary['total'],
            'cartCount' => $this->repository->countCurrentCart()
        ]);
    }

    public function add()
    {
        $result = $this->repository->addProduct(
            (int)($_POST['id_producto'] ?? 0),
            (int)($_POST['cantidad'] ?? 1)
        );

        $this->flash($result);
        $this->redirect($_SERVER['HTTP_REFERER'] ?? App::url('/products'));
    }

    public function update()
    {
        $result = $this->repository->updateProduct(
            (int)($_POST['id_producto'] ?? 0),
            trim((string)($_POST['accion'] ?? '')),
            isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null
        );

        $this->flash($result);
        $this->redirect(App::url('/cart'));
    }

    public function remove()
    {
        $result = $this->repository->removeProduct((int)($_POST['id_producto'] ?? 0));
        $this->flash($result);
        $this->redirect(App::url('/cart'));
    }

    public function clear()
    {
        $this->repository->clear();
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
