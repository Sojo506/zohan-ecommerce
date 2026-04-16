<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Editar cuenta</h4>

        <a href="<?= App::url('/admin/accounts') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/accounts/update') ?>" method="POST">

            <input type="hidden" name="id" value="<?= (int) ($account['ID_CUENTA'] ?? 0) ?>">

            <div class="mb-3">
                <label class="form-label">Usuario asociado</label>
                <select name="identificacion" class="form-select" required>
                    <option value="">Selecciona un usuario</option>
                    <?php foreach ($users as $user): ?>
                        <?php $nombreCompleto = trim(implode(' ', array_filter([
                            $user['NOMBRE'] ?? '',
                            $user['APELLIDO_PATERNO'] ?? '',
                            $user['APELLIDO_MATERNO'] ?? ''
                        ]))); ?>
                        <option value="<?= htmlspecialchars((string) $user['IDENTIFICACION']) ?>"
                            <?= ($user['IDENTIFICACION'] ?? '') === ($account['IDENTIFICACION'] ?? '') ? 'selected' : '' ?>>
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
                    value="<?= htmlspecialchars((string) ($account['USERNAME'] ?? '')) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva contrasena</label>
                <input type="password"
                    name="password"
                    class="form-control"
                    placeholder="Dejar vacio para conservar la actual"
                    minlength="8">
                <small class="text-muted">Solo se actualiza si escribes una nueva contrasena.</small>
            </div>

            <div class="mb-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= (int) $status['ID_ESTADO'] ?>"
                            <?= (int) ($status['ID_ESTADO'] ?? 0) === (int) ($account['ID_ESTADO'] ?? 0) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $status['NOMBRE']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-dark">
                <i class="bi bi-save"></i> Guardar cambios
            </button>

        </form>

    </div>

</div>
