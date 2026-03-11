<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Productos</h4>

        <a href="<?= App::url('/admin/products/create') ?>"
            class="btn btn-dark">
            Nuevo producto
        </a>

    </div>

    <div class="admin-panel-card-body">

        <table class="table table-striped">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($products as $p): ?>

                    <tr>

                        <td><?= $p['ID_PRODUCTO'] ?></td>

                        <td><?= $p['SKU'] ?></td>

                        <td><?= $p['NOMBRE'] ?></td>

                        <td>$<?= $p['PRECIO'] ?></td>

                        <td><?= $p['CATEGORIA'] ?></td>

                        <td><?= $p['MARCA'] ?></td>

                        <td>

                            <a href="<?= App::url('/admin/products/edit/' . $p['ID_PRODUCTO']) ?>"
                                class="btn btn-sm btn-outline-dark">
                                Editar
                            </a>

                            <a href="<?= App::url('/admin/products/delete/' . $p['ID_PRODUCTO']) ?>"
                                class="btn btn-sm btn-outline-danger btn-delete-product">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>