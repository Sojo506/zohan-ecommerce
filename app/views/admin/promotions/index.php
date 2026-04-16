<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Promociones</h4>
            <small class="text-muted">Gestión de promociones y descuentos</small>
        </div>

        <a href="<?= App::url('/admin/promotions/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva promoción
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Nombre</th>
                        <th>Descuento</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($promotions)): ?>

                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">
                                No hay promociones registradas
                            </td>
                        </tr>

                    <?php endif ?>

                    <?php foreach ($promotions as $p): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($p['NOMBRE']) ?>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    <?= $p['PORCENTAJE'] ?>%
                                </span>
                            </td>

                            <td class="text-muted">
                                <?= date('d M Y', strtotime($p['FECHA_INICIO'])) ?>
                            </td>

                            <td class="text-muted">
                                <?= date('d M Y', strtotime($p['FECHA_FIN'])) ?>
                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/promotions/edit/' . $p['ID_PROMOCION']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger btn-delete-promotion"
                                    data-url="<?= App::url('/admin/promotions/delete/' . $p['ID_PROMOCION']) ?>">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>