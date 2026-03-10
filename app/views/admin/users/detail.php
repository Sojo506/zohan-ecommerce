<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            <?= htmlspecialchars($user['NOMBRE']) ?>
            <?= htmlspecialchars($user['APELLIDO_PATERNO']) ?>
        </h4>

        <a href="<?= App::url('/admin/users') ?>" class="btn btn-dark">
            Volver
        </a>

    </div>


    <div class="admin-panel-card-body">

        <!-- Información básica -->

        <div class="row mb-4">

            <div class="col-md-4">
                <strong>Identificación</strong>
                <p><?= $user['IDENTIFICACION'] ?></p>
            </div>

            <div class="col-md-4">
                <strong>Rol</strong>
                <p>
                    <?= $user['ID_TIPO_USUARIO'] == 1 ? 'ADMIN' : 'CLIENTE' ?>
                </p>
            </div>

            <div class="col-md-4">
                <strong>Estado</strong>

                <?php if ($user['ID_ESTADO'] == 1): ?>

                    <span class="badge bg-success">Activo</span>

                <?php else: ?>

                    <span class="badge bg-danger">Inactivo</span>

                <?php endif ?>

            </div>

        </div>


        <hr>


        <!-- Correos -->

        <h5>Correos</h5>

        <div class="mb-4">

            <?php if (!empty($emails)): ?>

                <ul class="list-group">

                    <?php foreach ($emails as $e): ?>

                        <li class="list-group-item">
                            <?= htmlspecialchars($e['CORREO']) ?>
                        </li>

                    <?php endforeach ?>

                </ul>

            <?php else: ?>

                <p class="text-muted">No hay correos registrados.</p>

            <?php endif ?>

        </div>


        <!-- Teléfonos -->

        <h5>Teléfonos</h5>

        <div class="mb-4">

            <?php if (!empty($phones)): ?>

                <ul class="list-group">

                    <?php foreach ($phones as $p): ?>

                        <li class="list-group-item">
                            <?= htmlspecialchars($p['TELEFONO']) ?>
                        </li>

                    <?php endforeach ?>

                </ul>

            <?php else: ?>

                <p class="text-muted">No hay teléfonos registrados.</p>

            <?php endif ?>

        </div>


        <hr>


        <!-- Administración -->

        <h5>Administración</h5>

        <div class="d-flex flex-wrap gap-2">

            <a href="#"
                class="btn btn-success btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/status/1') ?>"
                data-action="activar">

                Activar

            </a>

            <a href="#"
                class="btn btn-danger btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/status/2') ?>"
                data-action="desactivar">

                Desactivar

            </a>

            <a href="#"
                class="btn btn-dark btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/role/1') ?>"
                data-action="admin">

                Hacer ADMIN

            </a>

            <a href="#"
                class="btn btn-secondary btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/role/2') ?>"
                data-action="cliente">

                Hacer CLIENTE

            </a>

        </div>
    </div>

</div>