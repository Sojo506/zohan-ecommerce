<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Crear producto</h4>

        <a href="<?= App::url('/admin/products') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form method="POST" action="<?= App::url('/admin/products/create') ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Descripción</label>

                <textarea name="descripcion"
                    rows="4"
                    class="form-control"></textarea>

            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number"
                        step="0.01"
                        name="precio"
                        class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock mínimo</label>
                    <input type="number"
                        name="stock_min"
                        class="form-control">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Categoría</label>

                    <select name="categoria" class="form-select" required>

                        <option value="">Seleccione</option>

                        <?php foreach ($categories as $c): ?>

                            <option value="<?= $c['ID_CATEGORIA'] ?>">
                                <?= htmlspecialchars($c['NOMBRE']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Marca</label>

                    <select name="marca" class="form-select" required>

                        <option value="">Seleccione</option>

                        <?php foreach ($brands as $b): ?>

                            <option value="<?= $b['ID_MARCA'] ?>">
                                <?= htmlspecialchars($b['NOMBRE']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <button class="btn btn-dark">
                <i class="bi bi-save"></i> Guardar producto
            </button>

        </form>

    </div>

</div>