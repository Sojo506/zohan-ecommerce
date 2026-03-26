<?php

require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/AuditModel.php';

class InvoiceAdminController extends Controller
{
    private function checkAdmin()
    {
        // Las facturas y sus cambios de estado están reservados al personal administrativo.
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

        $repo = new InvoiceModel();
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

        $repo = new InvoiceModel();

        // Reúne cabecera, detalle de productos y rastro del pago para la pantalla de detalle.
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

        $repo = new InvoiceModel();
        $repo->changeStatus($id, $status);

        // Si la auditoría está disponible, deja evidencia del cambio administrativo.
        if (class_exists('AuditModel')) {
            $audit = new AuditModel();
            $audit->log('CAMBIO ESTADO', 'FACTURA_TB');
        }

        header("Location: " . App::url('/admin/invoices/' . $id));
        exit;
    }
}
