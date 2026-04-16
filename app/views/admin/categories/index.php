<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Categorías</h4>
            <small class="text-muted">Gestión de categorías de productos</small>
        </div>

        <a href="<?= App::url('/admin/categories/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva categoría
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Nombre</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($categories as $c): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($c['NOMBRE']) ?>
                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/categories/edit/' . $c['ID_CATEGORIA']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger btn-delete-category"
                                    data-url="<?= App::url('/admin/categories/delete/' . $c['ID_CATEGORIA']) ?>">

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