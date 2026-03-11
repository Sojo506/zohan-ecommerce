<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Nueva promoción</h4>

        <a href="<?= App::url('/admin/promotions') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/promotions/create') ?>" method="POST">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Ej: Black Friday"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                    placeholder="Descripción de la promoción"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Porcentaje de descuento</label>
                <input
                    type="number"
                    name="percent"
                    class="form-control"
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
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha fin</label>
                    <input
                        type="date"
                        name="end"
                        class="form-control"
                        required>
                </div>

            </div>

            <button class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Crear promoción
            </button>

        </form>

    </div>

</div>