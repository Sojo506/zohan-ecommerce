<div class="admin-panel-card">

    <div class="admin-panel-card-header">

        <h4>Editar marca</h4>

    </div>

    <div class="admin-panel-card-body">

        <form method="POST" action="<?= App::url('/admin/brands/update') ?>">

            <input
                type="hidden"
                name="id"
                value="<?= $brand['ID_MARCA'] ?>">

            <div class="mb-3">

                <label class="form-label">

                    Nombre de la marca

                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($brand['NOMBRE']) ?>"
                    required>

            </div>

            <button class="btn btn-dark">

                Actualizar marca

            </button>

            <a
                href="<?= App::url('/admin/brands') ?>"
                class="btn btn-secondary">

                Cancelar

            </a>

        </form>

    </div>

</div>