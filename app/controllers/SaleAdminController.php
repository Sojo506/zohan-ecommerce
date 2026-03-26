<?php

require_once __DIR__ . '/../models/SaleModel.php';
require_once __DIR__ . '/../models/InvoiceModel.php';

class SaleAdminController extends Controller
{

    private function checkAdmin()
    {
        // El detalle de ventas solo debe estar disponible para usuarios administradores.
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

        $repo = new SaleModel();

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

        $repo = new SaleModel();
        $invoiceRepo = new InvoiceModel();

        // Une la venta con sus productos y la factura relacionada para la vista de detalle.
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
