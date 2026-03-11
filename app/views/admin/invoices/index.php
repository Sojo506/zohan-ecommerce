<div class="admin-panel-card">

    <div class="admin-panel-card-header">
        <h4 class="mb-0">Facturas</h4>
    </div>

    <div class="admin-panel-card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Venta</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Impuesto</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($invoices as $f): ?>
                        <tr>
                            <td><?= $f['ID_FACTURA'] ?></td>
                            <td>#<?= $f['ID_VENTA'] ?></td>
                            <td>
                                <?= htmlspecialchars($f['NOMBRE']) ?>
                                <?= htmlspecialchars($f['APELLIDO_PATERNO']) ?>
                            </td>
                            <td>$<?= number_format($f['SUBTOTAL'], 2) ?></td>
                            <td><?= number_format($f['IMPUESTO'], 2) ?>%</td>
                            <td><strong>$<?= number_format($f['TOTAL'], 2) ?></strong></td>
                            <td><?= htmlspecialchars($f['ESTADO']) ?></td>
                            <td><?= $f['FECHA_FACTURA'] ?></td>
                            <td>
                                <a href="<?= App::url('/admin/invoices/' . $f['ID_FACTURA']) ?>"
                                    class="btn btn-sm btn-dark">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>