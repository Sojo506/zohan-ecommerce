<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Editar marca</h4>

        <a href="<?= App::url('/admin/brands') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/brands/update') ?>" method="POST">

            <input type="hidden" name="id" value="<?= $brand['ID_MARCA'] ?>">

            <div class="mb-3">

                <label class="form-label">Nombre de la marca</label>

                <input type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($brand['NOMBRE']) ?>"
                    required>

            </div>

            <button class="btn btn-dark">
                <i class="bi bi-save"></i> Guardar cambios
            </button>

        </form>

    </div>

</div>