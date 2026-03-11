<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Auditoría del sistema</h4>
            <small class="text-muted">Registro de acciones realizadas en el sistema</small>
        </div>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Tabla</th>
                        <th>Fecha</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($logs)): ?>

                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">
                                No hay registros de auditoría
                            </td>
                        </tr>

                    <?php endif ?>

                    <?php foreach ($logs as $log): ?>

                        <tr>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($log['NOMBRE']) ?>
                                <?= htmlspecialchars($log['APELLIDO_PATERNO']) ?>

                            </td>

                            <td>

                                <?php
                                $accion = strtolower($log['ACCION']);
                                $badge = "secondary";

                                if ($accion === "insert") $badge = "success";
                                if ($accion === "update") $badge = "warning";
                                if ($accion === "delete") $badge = "danger";
                                ?>

                                <span class="badge bg-<?= $badge ?>">

                                    <?= strtoupper($log['ACCION']) ?>

                                </span>

                            </td>

                            <td>

                                <span class="badge bg-dark">

                                    <?= htmlspecialchars($log['TABLA_AFECTADA']) ?>

                                </span>

                            </td>

                            <td class="text-muted">

                                <?= date('d M Y H:i', strtotime($log['FECHA'])) ?>

                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>