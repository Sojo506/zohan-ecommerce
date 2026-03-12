<?php

require_once __DIR__ . '/../repositories/CartRepository.php';

class CartController extends Controller
{
    private CartRepository $repository;

    public function __construct()
    {
        $this->repository = new CartRepository();
        $this->repository->syncSessionCart();
    }

    public function index()
    {
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
        if (($result['success'] ?? false) === true) {
            $_SESSION['flash_success'] = $result['message'] ?? 'Operacion completada.';
            return;
        }

        $_SESSION['flash_error'] = $result['message'] ?? 'Ocurrio un error.';
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
