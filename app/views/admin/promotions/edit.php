<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Editar promoción</h4>

        <a href="<?= App::url('/admin/promotions') ?>" class="btn btn-dark">
            Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/promotions/update') ?>" method="POST">

            <input type="hidden" name="id" value="<?= $promotion['ID_PROMOCION'] ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= $promotion['NOMBRE'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea
                    name="description"
                    class="form-control"
                    rows="3"><?= $promotion['DESCRIPCION'] ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Porcentaje</label>
                <input
                    type="number"
                    name="percent"
                    class="form-control"
                    value="<?= $promotion['PORCENTAJE'] ?>"
                    min="1"
                    max="100"
                    required>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha inicio</label>
                    <input
                        type="date"
                        name="start"
                        class="form-control"
                        value="<?= date('Y-m-d', strtotime($promotion['FECHA_INICIO'])) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha fin</label>
                    <input
                        type="date"
                        name="end"
                        class="form-control"
                        value="<?= date('Y-m-d', strtotime($promotion['FECHA_FIN'])) ?>"
                        required>
                </div>

            </div>

            <button class="btn btn-success">
                Actualizar promoción
            </button>

        </form>

    </div>

</div>


<!-- PRODUCTOS ASIGNADOS -->

<div class="admin-panel-card mt-4">

    <div class="admin-panel-card-header">
        <h5>Productos en promoción</h5>
    </div>

    <div class="admin-panel-card-body">

        <ul class="list-group mb-3">

            <?php foreach ($assigned as $a): ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <?= $a['NOMBRE'] ?>

                    <a href="#"
                        class="btn btn-sm btn-outline-danger btn-delete-promotion-product"
                        data-url="<?= App::url('/admin/promotions/remove-product/' . $promotion['ID_PROMOCION'] . '/' . $a['ID_PRODUCTO']) ?>">

                        Eliminar

                    </a>

                </li>

            <?php endforeach; ?>

        </ul>

        <form action="<?= App::url('/admin/promotions/assign') ?>" method="POST">

            <input type="hidden" name="promotion" value="<?= $promotion['ID_PROMOCION'] ?>">

            <div class="row">

                <div class="col-md-8">

                    <select name="product" class="form-control">

                        <?php foreach ($products as $p): ?>

                            <option value="<?= $p['ID_PRODUCTO'] ?>">
                                <?= $p['NOMBRE'] ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-4">

                    <button class="btn btn-primary w-100">
                        Agregar producto
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>