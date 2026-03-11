<div class="admin-panel-card">

    <div class="admin-panel-card-header">

        <h4>Auditoría del sistema</h4>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Tabla</th>
                <th>Fecha</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($logs as $log): ?>

                <tr>

                    <td>

                        <?= $log['NOMBRE'] ?>
                        <?= $log['APELLIDO_PATERNO'] ?>

                    </td>

                    <td><?= $log['ACCION'] ?></td>

                    <td><?= $log['TABLA_AFECTADA'] ?></td>

                    <td><?= $log['FECHA'] ?></td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>