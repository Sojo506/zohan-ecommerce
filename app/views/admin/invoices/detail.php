<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Detalle de factura</h4>
            <small class="text-muted">Informacion de facturacion y pago asociado</small>
        </div>

        <a href="<?= App::url('/admin/invoices') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <?php
        $estado = strtolower((string) ($invoice['ESTADO'] ?? ''));
        $cliente = trim(implode(' ', array_filter([
            $invoice['NOMBRE'] ?? '',
            $invoice['APELLIDO_PATERNO'] ?? '',
            $invoice['APELLIDO_MATERNO'] ?? ''
        ])));
        $badge = 'secondary';

        if (in_array($estado, ['pagado', 'pagada', 'completado', 'completada'], true)) {
            $badge = 'success';
        } elseif ($estado === 'pendiente') {
            $badge = 'warning';
        } elseif (in_array($estado, ['cancelado', 'cancelada'], true)) {
            $badge = 'danger';
        }
        ?>

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <strong>Cliente</strong>
                <p class="mb-0">
                    <a href="<?= App::url('/admin/sales/' . $invoice['ID_VENTA']) ?>" class="text-decoration-none">
                        <?= htmlspecialchars($cliente !== '' ? $cliente : 'Ver venta') ?>
                    </a>
                </p>
            </div>

            <div class="col-md-3">
                <strong>Fecha</strong>
                <p class="mb-0">
                    <?= !empty($invoice['FECHA_FACTURA']) ? date('d M Y H:i', strtotime($invoice['FECHA_FACTURA'])) : 'N/D' ?>
                </p>
            </div>

            <div class="col-md-3">
                <strong>Estado</strong>
                <p class="mb-0">
                    <span class="badge bg-<?= $badge ?>">
                        <?= htmlspecialchars((string) ($invoice['ESTADO'] ?? 'N/D')) ?>
                    </span>
                </p>
            </div>

        </div>

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <small class="text-muted d-block">Subtotal</small>
                    <strong class="fs-5">$<?= number_format((float) ($invoice['SUBTOTAL'] ?? 0), 2) ?></strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <small class="text-muted d-block">Impuesto</small>
                    <strong class="fs-5"><?= number_format((float) ($invoice['IMPUESTO'] ?? 0), 2) ?>%</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <small class="text-muted d-block">Total</small>
                    <strong class="fs-5 text-success">$<?= number_format((float) ($invoice['TOTAL'] ?? 0), 2) ?></strong>
                </div>
            </div>

        </div>

        <hr>

        <h5 class="mb-3">Productos facturados</h5>

        <?php if (!empty($products)): ?>

            <div class="table-responsive mb-4">

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

                        <?php foreach ($products as $product): ?>

                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($product['NOMBRE']) ?></td>
                                <td><?= (int) $product['CANTIDAD'] ?></td>
                                <td>$<?= number_format((float) $product['PRECIO'], 2) ?></td>
                                <td class="text-end">$<?= number_format((float) $product['SUBTOTAL_LINEA'], 2) ?></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <p class="text-muted">No hay productos registrados en esta factura.</p>

        <?php endif; ?>

        <hr>

        <h5 class="mb-3">Pago PayPal</h5>

        <?php if (!empty($payment)): ?>

            <div class="row g-3">

                <div class="col-md-4">
                    <strong>Order ID</strong>
                    <p class="mb-0 text-break"><?= htmlspecialchars((string) ($payment['PAYPAL_ORDER_ID'] ?? 'N/D')) ?></p>
                </div>

                <div class="col-md-4">
                    <strong>Capture ID</strong>
                    <p class="mb-0 text-break"><?= htmlspecialchars((string) ($payment['PAYPAL_CAPTURE_ID'] ?? 'N/D')) ?></p>
                </div>

                <div class="col-md-4">
                    <strong>Monto</strong>
                    <p class="mb-0">$<?= number_format((float) ($payment['TOTAL'] ?? 0), 2) ?></p>
                </div>

                <div class="col-md-4">
                    <strong>Moneda</strong>
                    <p class="mb-0"><?= htmlspecialchars((string) ($payment['MONEDA'] ?? 'N/D')) ?></p>
                </div>

                <div class="col-md-4">
                    <strong>Estado del pago</strong>
                    <p class="mb-0"><?= htmlspecialchars((string) ($payment['ESTADO'] ?? 'N/D')) ?></p>
                </div>

                <div class="col-md-4">
                    <strong>Fecha de registro</strong>
                    <p class="mb-0">
                        <?= !empty($payment['FECHA_REGISTRO']) ? date('d M Y H:i', strtotime($payment['FECHA_REGISTRO'])) : 'N/D' ?>
                    </p>
                </div>

            </div>

        <?php else: ?>

            <p class="text-muted mb-0">No hay un pago PayPal registrado para esta factura.</p>

        <?php endif; ?>

    </div>

</div>
