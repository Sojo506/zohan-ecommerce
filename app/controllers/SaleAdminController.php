<?php

require_once __DIR__ . '/../repositories/SaleRepository.php';
require_once __DIR__ . '/../repositories/InvoiceRepository.php';

class SaleAdminController extends Controller
{

    private function checkAdmin()
    {
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

        $repo = new SaleRepository();

        $sales = $repo->all();

        $this->adminView('admin/sales/index', [
            'sales' => $sales,
            'pageTitle' => 'Ventas',
            'currentSection' => 'sales'
        ]);
    }

    public function detail($id)
    {
        $this->checkAdmin();

        $repo = new SaleRepository();
        $invoiceRepo = new InvoiceRepository();

        $sale = $repo->find($id);
        $products = $repo->products($id);

        $invoice = $invoiceRepo->findBySale($id);

        $this->adminView('admin/sales/detail', [
            'sale' => $sale,
            'products' => $products,
            'invoice' => $invoice,
            'pageTitle' => 'Detalle venta',
            'currentSection' => 'orders'
        ]);
    }
}
