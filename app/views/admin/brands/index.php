<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Marcas</h4>

        <a href="<?= App::url('/admin/brands/create') ?>" class="btn btn-dark">
            Nueva marca
        </a>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th></th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($brands as $b): ?>

                <tr>

                    <td><?= $b['ID_MARCA'] ?></td>

                    <td><?= htmlspecialchars($b['NOMBRE']) ?></td>

                    <td>

                        <a href="<?= App::url('/admin/brands/edit/' . $b['ID_MARCA']) ?>"
                            class="btn btn-sm btn-dark">

                            Editar

                        </a>

                        <a href="#"
                            class="btn btn-sm btn-outline-danger btn-delete-category"
                            data-url="<?= App::url('/admin/brands/delete/' . $b['ID_MARCA']) ?>">
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>