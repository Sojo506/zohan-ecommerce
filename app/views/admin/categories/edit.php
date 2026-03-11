<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Editar categoría</h4>

        <a href="<?= App::url('/admin/categories') ?>" class="btn btn-dark">
            Volver a categorías
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/categories/update') ?>" method="POST">

            <input type="hidden" name="id" value="<?= $category['ID_CATEGORIA'] ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($category['NOMBRE']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </form>

    </div>
</div>