<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Comentarios</h4>
            <small class="text-muted">Moderación de opiniones de clientes</small>
        </div>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Calificación</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end" style="width:200px;">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($comments)): ?>

                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">
                                No hay comentarios registrados
                            </td>
                        </tr>

                    <?php endif ?>

                    <?php foreach ($comments as $c): ?>

                        <tr>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($c['PRODUCTO']) ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($c['NOMBRE']) ?>
                                <?= htmlspecialchars($c['APELLIDO_PATERNO']) ?>

                            </td>

                            <td>

                                <span class="badge bg-warning text-dark">

                                    ⭐ <?= $c['CALIFICACION'] ?>/5

                                </span>

                            </td>

                            <td style="max-width:300px;" class="text-muted">

                                <?= htmlspecialchars($c['COMENTARIO']) ?>

                            </td>

                            <td class="text-muted">

                                <?= date('d M Y H:i', strtotime($c['FECHA_COMENTARIO'])) ?>

                            </td>

                            <td>

                                <?php if ($c['ESTADO'] === 'Activo'): ?>

                                    <span class="badge bg-success">
                                        Visible
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        Oculto
                                    </span>

                                <?php endif ?>

                            </td>

                            <td class="text-end">

                                <?php if ($c['ESTADO'] !== 'Activo'): ?>

                                    <a href="<?= App::url('/admin/comments/approve/' . $c['ID_COMENTARIO']) ?>"
                                        class="btn btn-sm btn-outline-success">

                                        <i class="bi bi-check-circle"></i>
                                    </a>

                                <?php endif ?>

                                <?php if ($c['ESTADO'] === 'Activo'): ?>

                                    <a href="<?= App::url('/admin/comments/hide/' . $c['ID_COMENTARIO']) ?>"
                                        class="btn btn-sm btn-outline-warning">

                                        <i class="bi bi-eye-slash"></i>
                                    </a>

                                <?php endif ?>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger btn-delete"
                                    data-url="<?= App::url('/admin/comments/delete/' . $c['ID_COMENTARIO']) ?>">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>