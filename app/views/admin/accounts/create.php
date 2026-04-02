<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Crear cuenta</h4>

        <a href="<?= App::url('/admin/accounts') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <?php $hasUsers = !empty($users); ?>

        <?php if (!$hasUsers): ?>
            <div class="alert alert-warning">
                No hay usuarios disponibles sin cuenta para registrar una nueva credencial.
            </div>
        <?php endif; ?>

        <form action="<?= App::url('/admin/accounts/create') ?>" method="POST">

            <div class="mb-3">
                <label class="form-label">Usuario asociado</label>
                <select name="identificacion" class="form-select" required <?= $hasUsers ? '' : 'disabled' ?>>
                    <option value="">Selecciona un usuario</option>
                    <?php foreach ($users as $user): ?>
                        <?php $nombreCompleto = trim(implode(' ', array_filter([
                            $user['NOMBRE'] ?? '',
                            $user['APELLIDO_PATERNO'] ?? '',
                            $user['APELLIDO_MATERNO'] ?? ''
                        ]))); ?>
                        <option value="<?= htmlspecialchars((string) $user['IDENTIFICACION']) ?>">
                            <?= htmlspecialchars($nombreCompleto . ' - ' . ($user['IDENTIFICACION'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text"
                    name="username"
                    class="form-control"
                    placeholder="Ej: maria.lopez"
                    required
                    <?= $hasUsers ? '' : 'disabled' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Contrasena</label>
                <input type="password"
                    name="password"
                    class="form-control"
                    placeholder="Minimo 8 caracteres"
                    minlength="8"
                    required
                    <?= $hasUsers ? '' : 'disabled' ?>>
            </div>

            <div class="mb-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required <?= $hasUsers ? '' : 'disabled' ?>>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= (int) $status['ID_ESTADO'] ?>" <?= (int) $status['ID_ESTADO'] === 1 ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $status['NOMBRE']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-dark" <?= $hasUsers ? '' : 'disabled' ?>>
                <i class="bi bi-plus-circle"></i> Crear cuenta
            </button>

        </form>

    </div>

</div>
