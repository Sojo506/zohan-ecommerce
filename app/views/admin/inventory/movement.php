<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Registrar movimiento</h4>

        <a href="<?= App::url('/admin/inventory') ?>" class="btn btn-dark">
            Volver a inventario
        </a>

    </div>

    <form method="POST">

        <div class="mb-3 p-3">

            <label>Producto</label>

            <select name="product" class="form-control">

                <?php foreach ($products as $p): ?>

                    <option value="<?= $p['ID_PRODUCTO'] ?>">

                        <?= $p['NOMBRE'] ?>

                    </option>

                <?php endforeach ?>

            </select>

        </div>


        <div class="mb-3 p-3">

            <label>Tipo movimiento</label>

            <select name="type" class="form-control">

                <?php foreach ($types as $t): ?>

                    <option value="<?= $t['ID_TIPO_MOVIMIENTO'] ?>">

                        <?= $t['NOMBRE'] ?>

                    </option>

                <?php endforeach ?>

            </select>

        </div>


        <div class="mb-3 p-3">

            <label>Cantidad</label>

            <input type="number" name="quantity" class="form-control">

        </div>

        <div class="mb-3 p-3">

            <label>Motivo</label>

            <textarea name="reason" class="form-control"></textarea>

        </div>

        <button class="btn btn-dark">

            Registrar movimiento

        </button>

    </form>

</div>