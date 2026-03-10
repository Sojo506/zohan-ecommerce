<div class="admin-panel-card">

    <div class="admin-panel-card-header">

        <h4>Usuarios</h4>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th></th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($users as $u): ?>

                <tr>

                    <td><?= $u['IDENTIFICACION'] ?></td>

                    <td>

                        <?= $u['NOMBRE'] ?>
                        <?= $u['APELLIDO_PATERNO'] ?>

                    </td>

                    <td><?= $u['TIPO'] ?></td>

                    <td><?= $u['ESTADO'] ?></td>

                    <td>

                        <a href="<?= App::url('/admin/users/' . $u['IDENTIFICACION']) ?>"
                            class="btn btn-sm btn-dark">

                            Ver

                        </a>

                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>