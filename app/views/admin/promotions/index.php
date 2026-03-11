<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Promociones</h4>

        <a href="<?= App::url('/admin/promotions/create') ?>" class="btn btn-dark">
            Nueva promoción
        </a>

    </div>

    <div class="admin-panel-card-body">

        <table class="table table-hover align-middle">

            <thead>

                <tr>
                    <th>Nombre</th>
                    <th>Descuento</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th style="width:150px;"></th>
                </tr>

            </thead>

            <tbody>

                <?php if (empty($promotions)): ?>

                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No hay promociones registradas
                        </td>
                    </tr>

                <?php endif ?>

                <?php foreach ($promotions as $p): ?>

                    <tr>

                        <td>
                            <strong><?= $p['NOMBRE'] ?></strong>
                        </td>

                        <td>
                            <span class="badge bg-success">
                                <?= $p['PORCENTAJE'] ?>%
                            </span>
                        </td>

                        <td>
                            <?= date('d/m/Y', strtotime($p['FECHA_INICIO'])) ?>
                        </td>

                        <td>
                            <?= date('d/m/Y', strtotime($p['FECHA_FIN'])) ?>
                        </td>

                        <td class="text-end">

                            <a href="<?= App::url('/admin/promotions/edit/' . $p['ID_PROMOCION']) ?>"
                                class="btn btn-sm btn-dark">

                                Editar
                            </a>

                            <a href="#"
                                class="btn btn-sm btn-outline-danger btn-delete-promotion"
                                data-url="<?= App::url('/admin/promotions/delete/' . $p['ID_PROMOCION']) ?>">

                                Eliminar

                            </a>

                        </td>

                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

    </div>

</div>