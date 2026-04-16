<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Registrar movimiento</h4>

        <a href="<?= App::url('/admin/inventory') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">Producto</label>

                <select name="product" class="form-select">

                    <?php foreach ($products as $p): ?>

                        <option value="<?= $p['ID_PRODUCTO'] ?>">

                            <?= htmlspecialchars($p['NOMBRE']) ?>

                        </option>

                    <?php endforeach ?>

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">Tipo de movimiento</label>

                <select name="type" class="form-select">

                    <?php foreach ($types as $t): ?>

                        <option value="<?= $t['ID_TIPO_MOVIMIENTO'] ?>">

                            <?= htmlspecialchars($t['NOMBRE']) ?>

                        </option>

                    <?php endforeach ?>

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">Cantidad</label>

                <input type="number"
                    name="quantity"
                    class="form-control"
                    required>

            </div>


            <div class="mb-3">

                <label class="form-label">Motivo</label>

                <textarea name="reason"
                    class="form-control"
                    rows="3"></textarea>

            </div>

            <button class="btn btn-dark">
                <i class="bi bi-save"></i> Registrar movimiento
            </button>

        </form>

    </div>

</div>