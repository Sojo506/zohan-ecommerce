<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Usuarios</h4>
            <small class="text-muted">Gestión de usuarios del sistema</small>
        </div>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($users as $u): ?>

                        <tr>

                            <td>

                                <span class="badge bg-dark">
                                    <?= $u['IDENTIFICACION'] ?>
                                </span>

                            </td>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($u['NOMBRE']) ?>
                                <?= htmlspecialchars($u['APELLIDO_PATERNO']) ?>

                            </td>

                            <td>

                                <?php if ($u['TIPO'] === 'ADMIN'): ?>

                                    <span class="badge bg-dark">ADMIN</span>

                                <?php else: ?>

                                    <span class="badge bg-primary">CLIENTE</span>

                                <?php endif ?>

                            </td>

                            <td>

                                <?php if ($u['ESTADO'] === 'Activo'): ?>

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        Inactivo
                                    </span>

                                <?php endif ?>

                            </td>

                            <td class="text-end">

                                <a href="<?= App::url('/admin/users/' . $u['IDENTIFICACION']) ?>"
                                    class="btn btn-sm btn-outline-dark">

                                    <i class="bi bi-person"></i> Ver

                                </a>

                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>