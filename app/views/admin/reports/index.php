<?php
$overview = $overview ?? [];
$monthlyRevenue = $monthlyRevenue ?? [];
$topProducts = $topProducts ?? [];
$criticalInventory = $criticalInventory ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1">Reportes administrativos</h2>
        <p class="text-muted mb-0">Genera PDFs con datos accionables de ventas, productos e inventario.</p>
    </div>

    <a href="<?= App::url('/admin/reports/pdf/executive-summary') ?>" class="btn btn-dark">
        <i class="bi bi-file-earmark-pdf"></i>
        Descargar resumen ejecutivo
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="admin-report-stat">
            <span class="admin-report-stat-label">Facturas emitidas</span>
            <h3 class="admin-report-stat-value"><?= number_format((int) ($overview['total_invoices'] ?? 0)) ?></h3>
            <small class="text-muted">Base para seguimiento comercial</small>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-report-stat">
            <span class="admin-report-stat-label">Facturacion total</span>
            <h3 class="admin-report-stat-value">$<?= number_format((float) ($overview['total_revenue'] ?? 0), 2) ?></h3>
            <small class="text-muted">Monto historico registrado</small>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-report-stat">
            <span class="admin-report-stat-label">Ultimos 30 dias</span>
            <h3 class="admin-report-stat-value">$<?= number_format((float) ($overview['revenue_last_30_days'] ?? 0), 2) ?></h3>
            <small class="text-muted">Ingreso reciente para pulso del negocio</small>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-report-stat">
            <span class="admin-report-stat-label">Ticket promedio</span>
            <h3 class="admin-report-stat-value">$<?= number_format((float) ($overview['average_ticket'] ?? 0), 2) ?></h3>
            <small class="text-muted">Valor medio por factura</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="admin-panel-card h-100">
            <div class="admin-panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">Evolucion mensual</h4>
                    <small class="text-muted">Ultimos meses facturados</small>
                </div>
                <span class="badge text-bg-light">
                    Pagadas: <?= number_format((int) ($overview['paid_invoices'] ?? 0)) ?>
                    | Pendientes: <?= number_format((int) ($overview['pending_invoices'] ?? 0)) ?>
                </span>
            </div>

            <div class="admin-panel-card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Periodo</th>
                                <th>Facturas</th>
                                <th class="text-end">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($monthlyRevenue)): ?>
                                <?php foreach ($monthlyRevenue as $row): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['period_label']) ?></td>
                                        <td><?= number_format((int) $row['invoices']) ?></td>
                                        <td class="text-end text-success fw-semibold">$<?= number_format((float) $row['revenue'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No hay datos mensuales disponibles.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="admin-panel-card h-100">
            <div class="admin-panel-card-header">
                <h4 class="mb-0">Reportes PDF</h4>
                <small class="text-muted">Descarga directa por necesidad</small>
            </div>

            <div class="admin-panel-card-body d-grid gap-3">
                <a href="<?= App::url('/admin/reports/pdf/executive-summary') ?>" class="admin-report-link">
                    <div>
                        <strong>Resumen ejecutivo</strong>
                        <div class="text-muted small">KPI de facturacion, tendencia reciente y top productos.</div>
                    </div>
                    <i class="bi bi-download"></i>
                </a>

                <a href="<?= App::url('/admin/reports/pdf/top-products') ?>" class="admin-report-link">
                    <div>
                        <strong>Top productos</strong>
                        <div class="text-muted small">Ranking por unidades vendidas e ingreso estimado.</div>
                    </div>
                    <i class="bi bi-download"></i>
                </a>

                <a href="<?= App::url('/admin/reports/pdf/critical-inventory') ?>" class="admin-report-link">
                    <div>
                        <strong>Inventario critico</strong>
                        <div class="text-muted small">Productos con riesgo de quiebre para reabastecimiento.</div>
                    </div>
                    <i class="bi bi-download"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h4 class="mb-0">Productos mas vendidos</h4>
                <small class="text-muted">Referencia rapida para compras y marketing</small>
            </div>

            <div class="admin-panel-card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>SKU</th>
                                <th>Unidades</th>
                                <th class="text-end">Ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topProducts)): ?>
                                <?php foreach ($topProducts as $row): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['NOMBRE']) ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row['SKU']) ?></td>
                                        <td><?= number_format((int) $row['units_sold']) ?></td>
                                        <td class="text-end text-success fw-semibold">$<?= number_format((float) $row['revenue'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Todavia no hay ventas para este ranking.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h4 class="mb-0">Stock critico</h4>
                <small class="text-muted">Lo que deberias reponer primero</small>
            </div>

            <div class="admin-panel-card-body">
                <?php if (!empty($criticalInventory)): ?>
                    <div class="d-grid gap-3">
                        <?php foreach ($criticalInventory as $row): ?>
                            <div class="admin-report-alert">
                                <div>
                                    <strong><?= htmlspecialchars($row['NOMBRE']) ?></strong>
                                    <div class="small text-muted">SKU: <?= htmlspecialchars($row['SKU']) ?></div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">Stock <?= (int) $row['STOCK'] ?> / Min <?= (int) $row['STOCK_MINIMO'] ?></div>
                                    <small class="text-danger">Faltante: <?= max(0, (int) $row['shortage']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        No hay productos con inventario critico.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>