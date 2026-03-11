<div class="admin-panel-card">

    <div class="admin-panel-card-header">
        <h4>Crear producto</h4>
    </div>

    <div class="admin-panel-card-body">

        <form method="POST" action="<?= App::url('/admin/products/create') ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Stock mínimo</label>
                    <input type="number" name="stock_min" class="form-control">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Categoría</label>

                    <select name="categoria" class="form-control" required>

                        <option value="">Seleccione</option>

                        <?php foreach ($categories as $c): ?>

                            <option value="<?= $c['ID_CATEGORIA'] ?>">
                                <?= htmlspecialchars($c['NOMBRE']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Marca</label>

                    <select name="marca" class="form-control" required>

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
                Guardar producto
            </button>

        </form>

    </div>

</div>