<div class="admin-panel-card">

    <div class="admin-panel-card-header">
        <h4 class="mb-0">Comentarios</h4>
    </div>

    <div class="admin-panel-card-body">

        <table class="table table-hover align-middle">

            <thead>

                <tr>
                    <th>Producto</th>
                    <th>Usuario</th>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="width:200px;"></th>
                </tr>

            </thead>

            <tbody>

                <?php if (empty($comments)): ?>

                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No hay comentarios registrados
                        </td>
                    </tr>

                <?php endif ?>

                <?php foreach ($comments as $c): ?>

                    <tr>

                        <td>
                            <strong><?= $c['PRODUCTO'] ?></strong>
                        </td>

                        <td>
                            <?= $c['NOMBRE'] ?>
                            <?= $c['APELLIDO_PATERNO'] ?>
                        </td>

                        <td>

                            <span class="badge bg-warning text-dark">

                                <?= $c['CALIFICACION'] ?>/5

                            </span>

                        </td>

                        <td style="max-width:300px;">

                            <?= htmlspecialchars($c['COMENTARIO']) ?>

                        </td>

                        <td>

                            <?= date('d/m/Y H:i', strtotime($c['FECHA_COMENTARIO'])) ?>

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
                                    class="btn btn-sm btn-success">

                                    Aprobar

                                </a>

                            <?php endif ?>

                            <?php if ($c['ESTADO'] === 'Activo'): ?>

                                <a href="<?= App::url('/admin/comments/hide/' . $c['ID_COMENTARIO']) ?>"
                                    class="btn btn-sm btn-warning">

                                    Ocultar

                                </a>

                            <?php endif ?>

                            <a href="#"
                                class="btn btn-sm btn-danger btn-delete"
                                data-url="<?= App::url('/admin/comments/delete/' . $c['ID_COMENTARIO']) ?>">

                                Eliminar

                            </a>

                        </td>

                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

    </div>

</div>