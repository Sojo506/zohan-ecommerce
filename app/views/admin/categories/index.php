<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Categorías</h4>

        <a href="<?= App::url('/admin/categories/create') ?>" class="btn btn-dark">
            Nueva categoría
        </a>

    </div>

    <div class="admin-panel-card-body">

        <table class="table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($categories as $c): ?>

                    <tr>

                        <td><?= $c['ID_CATEGORIA'] ?></td>

                        <td><?= htmlspecialchars($c['NOMBRE']) ?></td>

                        <td>

                            <a href="<?= App::url('/admin/categories/edit/' . $c['ID_CATEGORIA']) ?>" class="btn btn-sm btn-outline-dark">
                                Editar
                            </a>

                            <a href="#"
                                class="btn btn-sm btn-outline-danger btn-delete-category"
                                data-url="<?= App::url('/admin/categories/delete/' . $c['ID_CATEGORIA']) ?>">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

    </div>

</div>