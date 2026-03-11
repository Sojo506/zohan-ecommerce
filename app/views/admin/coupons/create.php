<!-- Crear cupón -->
<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Crear cupón</h4>

        <a href="<?= App::url('/admin/coupons') ?>" class="btn btn-dark">
            Volver a cupones
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form action="<?= App::url('/admin/coupons/create') ?>" method="POST">

            <div class="mb-3">
                <label for="code" class="form-label">Código</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="percent" class="form-label">Porcentaje</label>
                <input type="number" name="percent" id="percent" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="start" class="form-label">Fecha de inicio</label>
                <input type="date" name="start" id="start" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end" class="form-label">Fecha de fin</label>
                <input type="date" name="end" id="end" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="limit" class="form-label">Límite de uso</label>
                <input type="number" name="limit" id="limit" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Crear cupón</button>

        </form>

    </div>