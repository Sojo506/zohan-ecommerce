<?php

require_once __DIR__ . '/../repositories/InventoryRepository.php';
require_once __DIR__ . '/../repositories/InventoryMovementRepository.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class InventoryAdminController extends Controller
{
    private function checkAdmin()
    {
        // Los movimientos de stock se protegen porque alteran existencias reales.
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        if ($_SESSION['user']['tipo'] !== 'ADMIN') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $repo = new InventoryRepository();
        $inventory = $repo->all();

        $this->adminView('admin/inventory/index', [
            'inventory' => $inventory,
            'pageTitle' => 'Inventario',
            'currentSection' => 'inventory'
        ]);
    }

    public function movementForm()
    {
        $this->checkAdmin();

        $productRepo = new ProductRepository();
        $movementRepo = new InventoryMovementRepository();

        // El formulario necesita catálogo de productos y tipos de movimiento disponibles.
        $products = $productRepo->all();
        $types = $movementRepo->getTypes();

        $this->adminView('admin/inventory/movement', [
            'products' => $products,
            'types' => $types,
            'pageTitle' => 'Movimiento inventario',
            'currentSection' => 'inventory'
        ]);
    }

    public function registerMovement()
    {
        $this->checkAdmin();

        $movementRepo = new InventoryMovementRepository();
        $inventoryRepo = new InventoryRepository();

        $product = $_POST['product'];
        $quantity = $_POST['quantity'];
        $type = $_POST['type'];

        $inventory = $inventoryRepo->findByProduct($product);

        $currentStock = $inventory['STOCK'];

        // Tipo 1 suma existencias; cualquier otro tipo registrado resta unidades.
        if ($type == 1) {
            $newStock = $currentStock + $quantity;
        } else {
            $newStock = $currentStock - $quantity;
        }

        // Primero actualiza el stock vigente y luego deja trazado el movimiento en el histórico.
        $inventoryRepo->updateStock($product, $newStock);

        $movementRepo->create($_POST);

        header("Location: " . App::url('/admin/inventory'));
    }

    public function movements()
    {
        $this->checkAdmin();

        $repo = new InventoryMovementRepository();
        $movements = $repo->all();

        $this->adminView('admin/inventory/movements', [
            'movements' => $movements,
            'pageTitle' => 'Movimientos',
            'currentSection' => 'inventory'
        ]);
    }
}
