<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Crear cupón</h4>

        <a href="<?= App::url('/admin/coupons') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/coupons/create') ?>" method="POST">

            <div class="mb-3">

                <label class="form-label">Código</label>

                <input type="text"
                    name="code"
                    class="form-control"
                    placeholder="Ej: DESCUENTO10"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">Porcentaje</label>

                <input type="number"
                    name="percent"
                    class="form-control"
                    min="1"
                    max="100"
                    required>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Fecha de inicio</label>

                    <input type="date"
                        name="start"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Fecha de fin</label>

                    <input type="date"
                        name="end"
                        class="form-control"
                        required>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">Límite de uso</label>

                <input type="number"
                    name="limit"
                    class="form-control"
                    min="1"
                    required>

            </div>

            <button class="btn btn-dark">
                <i class="bi bi-plus-circle"></i> Crear cupón
            </button>

        </form>

    </div>

</div>