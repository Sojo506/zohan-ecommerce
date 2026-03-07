<div class="admin-section-header">
    <div>
        <h2 class="mb-1">Bienvenido al panel administrativo</h2>
        <p class="text-muted mb-0">
            Desde aquí podrás gestionar productos, ventas, usuarios, inventario y promociones.
        </p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="admin-stat-label">Productos</span>
            <h3 class="admin-stat-value">0</h3>
            <p class="admin-stat-text">Productos registrados en catálogo</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="admin-stat-label">Ventas</span>
            <h3 class="admin-stat-value">0</h3>
            <p class="admin-stat-text">Ventas procesadas</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="admin-stat-label">Usuarios</span>
            <h3 class="admin-stat-value">0</h3>
            <p class="admin-stat-text">Clientes registrados</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="admin-stat-label">Inventario</span>
            <h3 class="admin-stat-value">0</h3>
            <p class="admin-stat-text">Productos con control de stock</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h4 class="mb-0">Resumen general</h4>
            </div>
            <div class="admin-panel-card-body">
                <p class="mb-0">
                    Este dashboard será el centro de operaciones del administrador de Zohan Tech Store.
                    Aquí mostraremos métricas reales, actividad reciente, productos más vendidos y estado de pedidos.
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h4 class="mb-0">Accesos rápidos</h4>
            </div>
            <div class="admin-panel-card-body d-grid gap-2">
                <a href="<?= App::url('/admin/products') ?>" class="btn btn-dark">Gestionar productos</a>
                <a href="<?= App::url('/admin/orders') ?>" class="btn btn-outline-dark">Ver ventas</a>
                <a href="<?= App::url('/admin/users') ?>" class="btn btn-outline-dark">Administrar usuarios</a>
                <a href="<?= App::url('/admin/inventory') ?>" class="btn btn-outline-dark">Revisar inventario</a>
            </div>
        </div>
    </div>
</div>