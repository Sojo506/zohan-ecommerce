<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Facturas</h4>
            <small class="text-muted">Registro de facturación del sistema</small>
        </div>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Venta</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Impuesto</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($invoices as $f): ?>

                        <tr>

                            <td>
                                <span class="badge bg-dark">
                                    #<?= $f['ID_FACTURA'] ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    #<?= $f['ID_VENTA'] ?>
                                </span>
                            </td>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($f['NOMBRE']) ?>
                                <?= htmlspecialchars($f['APELLIDO_PATERNO']) ?>

                            </td>

                            <td>
                                $<?= number_format($f['SUBTOTAL'], 2) ?>
                            </td>

                            <td class="text-muted">
                                <?= number_format($f['IMPUESTO'], 2) ?>%
                            </td>

                            <td class="fw-bold text-success">
                                $<?= number_format($f['TOTAL'], 2) ?>
                            </td>

                            <td>

                                <?php
                                $estado = strtolower($f['ESTADO']);
                                $badge = "secondary";

                                if ($estado === "pagado") $badge = "success";
                                if ($estado === "pendiente") $badge = "warning";
                                if ($estado === "cancelado") $badge = "danger";
                                ?>

                                <span class="badge bg-<?= $badge ?>">
                                    <?= htmlspecialchars($f['ESTADO']) ?>
                                </span>

                            </td>

                            <td class="text-muted">
                                <?= date('d M Y', strtotime($f['FECHA_FACTURA'])) ?>
                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/invoices/' . $f['ID_FACTURA']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-receipt"></i> Ver

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>