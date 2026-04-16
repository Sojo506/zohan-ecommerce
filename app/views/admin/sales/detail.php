<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Detalle de venta</h4>
            <small class="text-muted">Resumen completo de la venta seleccionada</small>
        </div>

        <a href="<?= App::url('/admin/sales') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <?php if (empty($sale)): ?>

            <div class="alert alert-warning mb-0">
                La venta solicitada no existe o ya no esta disponible.
            </div>

        <?php else: ?>

            <?php
            $estado = strtolower((string) ($sale['ID_ESTADO'] ?? ''));
            $cliente = trim(implode(' ', array_filter([
                $sale['NOMBRE'] ?? '',
                $sale['APELLIDO_PATERNO'] ?? '',
                $sale['APELLIDO_MATERNO'] ?? ''
            ])));
            $badge = 'secondary';

            if (in_array($estado, ['4', 'pagada', 'pagado', 'completada', 'completado'], true)) {
                $badge = 'success';
            } elseif (in_array($estado, ['1', 'pendiente'], true)) {
                $badge = 'warning';
            } elseif (in_array($estado, ['2', 'cancelada', 'cancelado'], true)) {
                $badge = 'danger';
            }
            ?>

            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <strong>Cliente</strong>
                    <p class="mb-0">
                        <?= htmlspecialchars($cliente !== '' ? $cliente : 'N/D') ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <strong>Fecha</strong>
                    <p class="mb-0">
                        <?= !empty($sale['FECHA_VENTA']) ? date('d M Y H:i', strtotime($sale['FECHA_VENTA'])) : 'N/D' ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <strong>Estado</strong>
                    <p class="mb-0">
                        <span class="badge bg-<?= $badge ?>">
                            <?= htmlspecialchars((string) (
                                $sale['ESTADO_NOMBRE'] ?? ($sale['ID_ESTADO'] == 1 ? 'Activo' : ($sale['ID_ESTADO'] == 2 ? 'Inactivo' : ($sale['ID_ESTADO'] == 3 ? 'Pendiente' : 'Desconocido')))
                            )) ?>
                        </span>
                    </p>
                </div>

            </div>

            <hr>

            <div class="d-flex flex-wrap gap-2 mb-4">

                <?php if (!empty($invoice)): ?>

                    <a href="<?= App::url('/admin/invoices/' . $invoice['ID_FACTURA']) ?>" class="btn btn-outline-primary">
                        <i class="bi bi-receipt"></i> Ver factura
                    </a>

                <?php else: ?>

                    <span class="text-muted">Esta venta no tiene factura asociada.</span>

                <?php endif; ?>

            </div>

            <h5 class="mb-3">Productos</h5>

            <?php if (!empty($products)): ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php $totalVenta = 0; ?>

                            <?php foreach ($products as $product): ?>

                                <?php $subtotal = ((float) $product['CANTIDAD']) * ((float) $product['PRECIO']); ?>
                                <?php $totalVenta += $subtotal; ?>

                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($product['NOMBRE']) ?></td>
                                    <td><?= (int) $product['CANTIDAD'] ?></td>
                                    <td>$<?= number_format((float) $product['PRECIO'], 2) ?></td>
                                    <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total estimado</th>
                                <th class="text-end">$<?= number_format($totalVenta, 2) ?></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            <?php else: ?>

                <p class="text-muted mb-0">No hay productos registrados para esta venta.</p>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>
