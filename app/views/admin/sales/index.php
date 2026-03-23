<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Ventas</h4>
            <small class="text-muted">Registro de ventas realizadas</small>
        </div>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($sales as $sale): ?>

                        <tr>

                            <td>

                                <span class="badge bg-dark">
                                    #<?= $sale['ID_VENTA'] ?>
                                </span>

                            </td>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($sale['NOMBRE']) ?>
                                <?= htmlspecialchars($sale['APELLIDO_PATERNO']) ?>

                            </td>

                            <td class="text-muted">

                                <?= date('d M Y H:i', strtotime($sale['FECHA_VENTA'])) ?>

                            </td>

                            <td>

                                <?php
                                $estado = strtolower($sale['ESTADO']);
                                $badge = "secondary";

                                if ($estado === "pagada") $badge = "success";
                                if ($estado === "pendiente") $badge = "warning";
                                if ($estado === "cancelada") $badge = "danger";
                                ?>

                                <span class="badge bg-<?= $badge ?>">
                                    <?= htmlspecialchars($sale['ESTADO']) ?>
                                </span>

                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/sales/' . $sale['ID_VENTA']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-eye"></i> Ver

                                </a>

                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>