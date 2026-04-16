<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Cupones</h4>
            <small class="text-muted">Gestión de descuentos y promociones</small>
        </div>

        <a href="<?= App::url('/admin/coupons/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nuevo cupón
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Código</th>
                        <th>Descuento</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($coupons as $c): ?>

                        <tr>

                            <td>

                                <span class="badge bg-dark">
                                    <?= htmlspecialchars($c['CODIGO']) ?>
                                </span>

                            </td>

                            <td class="fw-semibold text-success">

                                <?= $c['PORCENTAJE'] ?>%

                            </td>

                            <td class="text-muted">

                                <?= date('d M Y', strtotime($c['FECHA_INICIO'])) ?>

                            </td>

                            <td class="text-muted">

                                <?= date('d M Y', strtotime($c['FECHA_FIN'])) ?>

                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/coupons/edit/' . $c['ID_CUPON']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger btn-delete-coupon"
                                    data-url="<?= App::url('/admin/coupons/delete/' . $c['ID_CUPON']) ?>">

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