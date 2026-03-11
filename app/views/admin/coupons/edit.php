<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-end align-items-center">

        <a href="<?= App::url('/admin/coupons') ?>" class="btn btn-outline-dark">
            Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/coupons/update') ?>" method="POST">

            <!-- ID oculto -->
            <input type="hidden" name="id" value="<?= $coupon['ID_CUPON'] ?>">

            <div class="mb-3">
                <label for="code" class="form-label">Código</label>

                <input
                    type="text"
                    name="code"
                    id="code"
                    class="form-control"
                    value="<?= htmlspecialchars($coupon['CODIGO']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="percent" class="form-label">Porcentaje</label>

                <input
                    type="number"
                    name="percent"
                    id="percent"
                    class="form-control"
                    min="1"
                    max="100"
                    value="<?= $coupon['PORCENTAJE'] ?>"
                    required>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="start" class="form-label">Fecha de inicio</label>

                    <input
                        type="date"
                        name="start"
                        id="start"
                        class="form-control"
                        value="<?= $coupon['FECHA_INICIO'] ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end" class="form-label">Fecha de fin</label>

                    <input
                        type="date"
                        name="end"
                        id="end"
                        class="form-control"
                        value="<?= $coupon['FECHA_FIN'] ?>"
                        required>
                </div>

            </div>

            <div class="mb-4">
                <label for="limit" class="form-label">Límite de uso</label>

                <input
                    type="number"
                    name="limit"
                    id="limit"
                    class="form-control"
                    min="1"
                    value="<?= $coupon['USO_MAXIMO'] ?>"
                    required>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-dark">
                    Guardar cambios
                </button>

                <a href="<?= App::url('/admin/coupons') ?>" class="btn btn-secondary">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>