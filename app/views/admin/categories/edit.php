<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Editar categoría</h4>

        <a href="<?= App::url('/admin/categories') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/categories/update') ?>" method="POST">

            <input type="hidden" name="id" value="<?= $category['ID_CATEGORIA'] ?>">

            <div class="mb-3">

                <label for="name" class="form-label">Nombre de la categoría</label>

                <input type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    value="<?= htmlspecialchars($category['NOMBRE']) ?>"
                    required>

            </div>

            <button type="submit" class="btn btn-dark">
                <i class="bi bi-save"></i> Guardar cambios
            </button>

        </form>

    </div>

</div>