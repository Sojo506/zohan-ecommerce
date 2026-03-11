<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">

                <?= htmlspecialchars($user['NOMBRE']) ?>
                <?= htmlspecialchars($user['APELLIDO_PATERNO']) ?>

            </h4>

            <small class="text-muted">
                Perfil de usuario
            </small>
        </div>

        <a href="<?= App::url('/admin/users') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>


    <div class="admin-panel-card-body">

        <!-- Información básica -->

        <div class="row mb-4">

            <div class="col-md-4">

                <strong>Identificación</strong>

                <p class="mb-0">
                    <?= $user['IDENTIFICACION'] ?>
                </p>

            </div>

            <div class="col-md-4">

                <strong>Rol</strong>

                <p class="mb-0">

                    <?php if ($user['ID_TIPO_USUARIO'] == 1): ?>

                        <span class="badge bg-dark">
                            ADMIN
                        </span>

                    <?php else: ?>

                        <span class="badge bg-primary">
                            CLIENTE
                        </span>

                    <?php endif ?>

                </p>

            </div>

            <div class="col-md-4">

                <strong>Estado</strong>

                <p class="mb-0">

                    <?php if ($user['ID_ESTADO'] == 1): ?>

                        <span class="badge bg-success">
                            Activo
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Inactivo
                        </span>

                    <?php endif ?>

                </p>

            </div>

        </div>


        <hr>


        <!-- Correos -->

        <h5 class="mb-3">Correos</h5>

        <?php if (!empty($emails)): ?>

            <ul class="list-group mb-4">

                <?php foreach ($emails as $e): ?>

                    <li class="list-group-item">

                        <i class="bi bi-envelope"></i>

                        <?= htmlspecialchars($e['CORREO']) ?>

                    </li>

                <?php endforeach ?>

            </ul>

        <?php else: ?>

            <p class="text-muted mb-4">
                No hay correos registrados.
            </p>

        <?php endif ?>


        <!-- Teléfonos -->

        <h5 class="mb-3">Teléfonos</h5>

        <?php if (!empty($phones)): ?>

            <ul class="list-group mb-4">

                <?php foreach ($phones as $p): ?>

                    <li class="list-group-item">

                        <i class="bi bi-telephone"></i>

                        <?= htmlspecialchars($p['TELEFONO']) ?>

                    </li>

                <?php endforeach ?>

            </ul>

        <?php else: ?>

            <p class="text-muted mb-4">
                No hay teléfonos registrados.
            </p>

        <?php endif ?>


        <hr>


        <!-- Administración -->

        <h5 class="mb-3">Administración</h5>

        <div class="d-flex flex-wrap gap-2">

            <a href="#"
                class="btn btn-success btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/status/1') ?>"
                data-action="activar">

                <i class="bi bi-check-circle"></i> Activar

            </a>

            <a href="#"
                class="btn btn-danger btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/status/2') ?>"
                data-action="desactivar">

                <i class="bi bi-x-circle"></i> Desactivar

            </a>

            <a href="#"
                class="btn btn-dark btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/role/1') ?>"
                data-action="admin">

                <i class="bi bi-shield-lock"></i> Hacer ADMIN

            </a>

            <a href="#"
                class="btn btn-secondary btn-user-action"
                data-url="<?= App::url('/admin/users/' . $user['IDENTIFICACION'] . '/role/2') ?>"
                data-action="cliente">

                <i class="bi bi-person"></i> Hacer CLIENTE

            </a>

        </div>

    </div>

</div>