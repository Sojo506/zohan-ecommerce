<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Crear categoría</h4>

        <a href="<?= App::url('/admin/categories') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/categories/create') ?>" method="POST">

            <div class="mb-3">

                <label for="name" class="form-label">Nombre de la categoría</label>

                <input type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    placeholder="Ej: Laptops, Accesorios..."
                    required>

            </div>

            <button type="submit" class="btn btn-dark">
                <i class="bi bi-plus-circle"></i> Crear categoría
            </button>

        </form>

    </div>

</div>