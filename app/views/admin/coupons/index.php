<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Cupones</h4>

        <a href="<?= App::url('/admin/coupons/create') ?>" class="btn btn-dark">
            Nuevo cupón
        </a>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>Código</th>
                <th>%</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th></th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($coupons as $c): ?>

                <tr>

                    <td><?= $c['CODIGO'] ?></td>

                    <td><?= $c['PORCENTAJE'] ?>%</td>

                    <td><?= $c['FECHA_INICIO'] ?></td>

                    <td><?= $c['FECHA_FIN'] ?></td>

                    <td>

                        <a href="<?= App::url('/admin/coupons/edit/' . $c['ID_CUPON']) ?>"
                            class="btn btn-sm btn-dark">

                            Editar

                        </a>

                        <a href="#"
                            class="btn btn-sm btn-danger btn-delete-category"
                            data-url="<?= App::url('/admin/coupons/delete/' . $c['ID_CUPON']) ?>">

                            Eliminar

                        </a>
                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>