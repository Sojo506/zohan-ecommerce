<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Marcas</h4>
            <small class="text-muted">Gestión de marcas del catálogo</small>
        </div>

        <a href="<?= App::url('/admin/brands/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva marca
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($brands as $b): ?>

                        <tr>

                            <td>
                                <span class="badge bg-dark">
                                    #<?= $b['ID_MARCA'] ?>
                                </span>
                            </td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($b['NOMBRE']) ?>
                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/brands/edit/' . $b['ID_MARCA']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger btn-delete-brand"
                                    data-url="<?= App::url('/admin/brands/delete/' . $b['ID_MARCA']) ?>">

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