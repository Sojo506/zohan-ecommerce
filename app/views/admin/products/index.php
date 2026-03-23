<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Productos</h4>
            <small class="text-muted">Gestión de productos del catálogo</small>
        </div>

        <a href="<?= App::url('/admin/products/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nuevo producto
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($products as $p): ?>

                        <tr>

                            <td>
                                <span class="badge bg-dark">
                                    #<?= $p['ID_PRODUCTO'] ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($p['SKU']) ?></td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($p['NOMBRE']) ?>
                            </td>

                            <td class="text-success fw-semibold">
                                $<?= number_format($p['PRECIO'], 2) ?>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($p['CATEGORIA']) ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= htmlspecialchars($p['MARCA']) ?>
                                </span>
                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/products/edit/' . $p['ID_PRODUCTO']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="<?= App::url('/admin/products/delete/' . $p['ID_PRODUCTO']) ?>"
                                    class="btn btn-sm btn-outline-danger btn-delete-product">

                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>