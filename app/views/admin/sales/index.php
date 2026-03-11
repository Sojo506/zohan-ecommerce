<div class="admin-panel-card">

    <div class="admin-panel-card-header">

        <h4>Ventas</h4>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($sales as $sale): ?>

                <tr>

                    <td><?= $sale['ID_VENTA'] ?></td>

                    <td>
                        <?= $sale['NOMBRE'] ?>
                        <?= $sale['APELLIDO_PATERNO'] ?>
                    </td>

                    <td><?= $sale['FECHA_VENTA'] ?></td>

                    <td><?= $sale['ESTADO'] ?></td>

                    <td>

                        <a href="<?= App::url('/admin/sales/' . $sale['ID_VENTA']) ?>"
                            class="btn btn-sm btn-dark">

                            Ver detalle

                        </a>

                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>