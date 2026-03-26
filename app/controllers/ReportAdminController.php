<?php

require_once __DIR__ . '/../models/ReportModel.php';
require_once __DIR__ . '/../services/SimplePdfService.php';

class ReportAdminController extends Controller
{
    private function checkAdmin()
    {
        // Los reportes exponen métricas sensibles, por eso se limitan al rol administrador.
        if (!isset($_SESSION['user'])) {
            header('Location: ' . App::url('/login'));
            exit;
        }

        if ($_SESSION['user']['tipo'] !== 'ADMIN') {
            http_response_code(403);
            echo 'Acceso denegado.';
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $repo = new ReportModel();

        $this->adminView('admin/reports/index', [
            'pageTitle' => 'Reportes',
            'currentSection' => 'reports',
            'overview' => $repo->salesOverview(),
            'monthlyRevenue' => $repo->monthlyRevenue(),
            'topProducts' => $repo->topSellingProducts(5),
            'criticalInventory' => $repo->criticalInventory(6)
        ]);
    }

    public function download($type)
    {
        $this->checkAdmin();

        $repo = new ReportModel();
        $pdf = new SimplePdfService();
        $report = $this->buildReport($repo, $type);

        if ($report === null) {
            http_response_code(404);
            echo 'Reporte no encontrado.';
            return;
        }

        // El controlador arma la estructura del reporte y delega el render final al servicio PDF.
        $pdf->download($report['filename'], $report['title'], $report['sections']);
    }

    private function buildReport(ReportModel $repo, string $type): ?array
    {
        // Mapea el slug de la ruta al generador de contenido correspondiente.
        switch ($type) {
            case 'executive-summary':
                return $this->executiveSummaryReport($repo);
            case 'top-products':
                return $this->topProductsReport($repo);
            case 'critical-inventory':
                return $this->criticalInventoryReport($repo);
            default:
                return null;
        }
    }

    private function executiveSummaryReport(ReportModel $repo): array
    {
        $overview = $repo->salesOverview();
        $monthlyRevenue = $repo->monthlyRevenue();
        $topProducts = $repo->topSellingProducts(5);

        // Convierte métricas agregadas en líneas de texto listas para imprimir en PDF.
        $trendLines = [];
        foreach ($monthlyRevenue as $row) {
            $trendLines[] = sprintf(
                '%s | Facturas: %s | Ingresos: $%s',
                $row['period_label'],
                number_format((int) $row['invoices']),
                number_format((float) $row['revenue'], 2)
            );
        }

        $topProductLines = [];
        foreach ($topProducts as $index => $row) {
            $topProductLines[] = sprintf(
                '%d. %s (%s) | Unidades: %s | Ingreso: $%s',
                $index + 1,
                $row['NOMBRE'],
                $row['SKU'],
                number_format((int) $row['units_sold']),
                number_format((float) $row['revenue'], 2)
            );
        }

        return [
            'filename' => 'reporte-resumen-ejecutivo.pdf',
            'title' => 'Reporte Ejecutivo',
            'sections' => [
                [
                    'title' => 'Resumen comercial',
                    'lines' => [
                        'Facturas emitidas: ' . number_format((int) ($overview['total_invoices'] ?? 0)),
                        'Ingreso total facturado: $' . number_format((float) ($overview['total_revenue'] ?? 0), 2),
                        'Ticket promedio: $' . number_format((float) ($overview['average_ticket'] ?? 0), 2),
                        'Ingresos en los ultimos 30 dias: $' . number_format((float) ($overview['revenue_last_30_days'] ?? 0), 2),
                        'Facturas pagadas: ' . number_format((int) ($overview['paid_invoices'] ?? 0)),
                        'Facturas pendientes: ' . number_format((int) ($overview['pending_invoices'] ?? 0)),
                    ]
                ],
                ['title' => 'Evolucion mensual', 'lines' => $trendLines ?: ['No hay ventas registradas.']],
                ['title' => 'Productos lideres', 'lines' => $topProductLines ?: ['No hay productos vendidos.']],
            ]
        ];
    }

    private function topProductsReport(ReportModel $repo): array
    {
        $topProducts = $repo->topSellingProducts(12);
        $lines = [];

        foreach ($topProducts as $index => $row) {
            $lines[] = sprintf(
                '%d. %s | SKU: %s | Unidades vendidas: %s | Facturacion estimada: $%s',
                $index + 1,
                $row['NOMBRE'],
                $row['SKU'],
                number_format((int) $row['units_sold']),
                number_format((float) $row['revenue'], 2)
            );
        }

        return [
            'filename' => 'reporte-productos-top.pdf',
            'title' => 'Reporte de Productos Mas Vendidos',
            'sections' => [
                ['title' => 'Ranking comercial', 'lines' => $lines ?: ['No hay ventas registradas para generar este reporte.']]
            ]
        ];
    }

    private function criticalInventoryReport(ReportModel $repo): array
    {
        $criticalInventory = $repo->criticalInventory(15);
        $lines = [];

        foreach ($criticalInventory as $index => $row) {
            $lines[] = sprintf(
                '%d. %s | SKU: %s | Stock actual: %s | Minimo: %s | Faltante: %s',
                $index + 1,
                $row['NOMBRE'],
                $row['SKU'],
                number_format((int) $row['STOCK']),
                number_format((int) $row['STOCK_MINIMO']),
                number_format(max(0, (int) $row['shortage']))
            );
        }

        return [
            'filename' => 'reporte-inventario-critico.pdf',
            'title' => 'Reporte de Inventario Critico',
            'sections' => [
                ['title' => 'Productos con riesgo de quiebre', 'lines' => $lines ?: ['No hay productos en stock critico.']]
            ]
        ];
    }
}
