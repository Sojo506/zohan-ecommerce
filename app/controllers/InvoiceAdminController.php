<?php

require_once __DIR__ . '/../repositories/InvoiceRepository.php';
require_once __DIR__ . '/../repositories/AuditRepository.php';

class InvoiceAdminController extends Controller
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

        $repo = new InvoiceRepository();
        $invoices = $repo->all();

        $this->adminView('admin/invoices/index', [
            'invoices' => $invoices,
            'pageTitle' => 'Facturas',
            'currentSection' => 'invoices'
        ]);
    }

    public function detail($id)
    {
        $this->checkAdmin();

        $repo = new InvoiceRepository();

        $invoice = $repo->find($id);
        $products = $repo->saleProducts($id);
        $payment = $repo->paypalPayment($id);

        if (!$invoice) {
            echo "Factura no encontrada.";
            return;
        }

        $this->adminView('admin/invoices/detail', [
            'invoice' => $invoice,
            'products' => $products,
            'payment' => $payment,
            'pageTitle' => 'Detalle factura',
            'currentSection' => 'invoices'
        ]);
    }

    public function changeStatus($id, $status)
    {
        $this->checkAdmin();

        $repo = new InvoiceRepository();
        $repo->changeStatus($id, $status);

        if (class_exists('AuditRepository')) {
            $audit = new AuditRepository();
            $audit->log('CAMBIO ESTADO', 'FACTURA_TB');
        }

        header("Location: " . App::url('/admin/invoices/' . $id));
        exit;
    }
}
