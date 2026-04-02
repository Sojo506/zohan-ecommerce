<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Cuentas</h4>
            <small class="text-muted">Gestion de accesos registrados en el sistema</small>
        </div>

        <a href="<?= App::url('/admin/accounts/create') ?>" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva cuenta
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Username</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Ultimo login</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($accounts)): ?>

                        <?php foreach ($accounts as $account): ?>

                            <?php
                            $nombreCompleto = trim(implode(' ', array_filter([
                                $account['NOMBRE'] ?? '',
                                $account['APELLIDO_PATERNO'] ?? '',
                                $account['APELLIDO_MATERNO'] ?? ''
                            ])));
                            $estado = strtolower((string) ($account['ESTADO'] ?? ''));
                            $tipoUsuario = strtoupper((string) ($account['TIPO_USUARIO'] ?? 'CLIENTE'));
                            ?>

                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($nombreCompleto !== '' ? $nombreCompleto : 'Sin nombre') ?></div>
                                    <small class="text-muted"><?= htmlspecialchars((string) ($account['IDENTIFICACION'] ?? '')) ?></small>
                                </td>

                                <td><?= htmlspecialchars((string) ($account['USERNAME'] ?? '')) ?></td>

                                <td>
                                    <span class="badge bg-<?= $tipoUsuario === 'ADMIN' ? 'dark' : 'primary' ?>">
                                        <?= htmlspecialchars($tipoUsuario) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-<?= $estado === 'activo' ? 'success' : ($estado === 'pendiente' ? 'warning' : 'secondary') ?>">
                                        <?= htmlspecialchars((string) ($account['ESTADO'] ?? 'N/D')) ?>
                                    </span>
                                </td>

                                <td class="text-muted">
                                    <?= !empty($account['ULTIMO_LOGIN']) ? date('d M Y H:i', strtotime($account['ULTIMO_LOGIN'])) : 'Sin registro' ?>
                                </td>

                                <td class="text-end">
                                    <a href="<?= App::url('/admin/accounts/edit/' . $account['ID_CUENTA']) ?>"
                                        class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="<?= App::url('/admin/accounts/delete/' . $account['ID_CUENTA']) ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Deseas inactivar esta cuenta?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay cuentas registradas.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
