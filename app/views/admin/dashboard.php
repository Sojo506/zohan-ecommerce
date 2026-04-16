<!-- HEADER -->

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

    <div>
        <h2 class="mb-1">Panel administrativo</h2>

        <p class="text-muted mb-0">
            Bienvenido al panel de control de <strong>Zohan Tech Store</strong>.
        </p>
    </div>

    <span class="text-muted">
        <i class="bi bi-calendar3"></i>
        <?= date('d M Y') ?>
    </span>

</div>



<!-- STATS -->

<div class="row g-4 mb-4">

    <!-- PRODUCTOS -->

    <div class="col-md-6 col-xl-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body d-flex align-items-center">

                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="bi bi-box fs-4"></i>
                </div>

                <div>

                    <div class="text-muted small">
                        Productos
                    </div>

                    <h4 class="mb-0">
                        <?= $stats['products'] ?? 0 ?>
                    </h4>

                    <small class="text-muted">
                        Productos activos
                    </small>

                </div>

            </div>

        </div>

    </div>



    <!-- VENTAS -->

    <div class="col-md-6 col-xl-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body d-flex align-items-center">

                <div class="bg-success text-white rounded p-3 me-3">
                    <i class="bi bi-cart-check fs-4"></i>
                </div>

                <div>

                    <div class="text-muted small">
                        Ventas
                    </div>

                    <h4 class="mb-0">
                        <?= $stats['sales'] ?? 0 ?>
                    </h4>

                    <small class="text-muted">
                        Ventas registradas
                    </small>

                </div>

            </div>

        </div>

    </div>



    <!-- USUARIOS -->

    <div class="col-md-6 col-xl-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body d-flex align-items-center">

                <div class="bg-dark text-white rounded p-3 me-3">
                    <i class="bi bi-people fs-4"></i>
                </div>

                <div>

                    <div class="text-muted small">
                        Usuarios
                    </div>

                    <h4 class="mb-0">
                        <?= $stats['users'] ?? 0 ?>
                    </h4>

                    <small class="text-muted">
                        Clientes registrados
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="row g-4">

    <!-- ÚLTIMAS VENTAS -->

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Últimas ventas
                </h5>

                <a href="<?= App::url('/admin/sales') ?>" class="btn btn-sm btn-outline-dark">
                    Ver todas
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($recentSales)): ?>

                            <?php foreach ($recentSales as $sale): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($sale['user']) ?>
                                    </td>

                                    <td class="text-success fw-semibold">
                                        $<?= number_format($sale['total'], 2) ?>
                                    </td>

                                    <td class="text-muted">
                                        <?= date('d M Y', strtotime($sale['date'])) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="4" class="text-center text-muted py-4">
                                    No hay ventas recientes
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <!-- QUICK ACTIONS -->

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Accesos rápidos
                </h5>

            </div>

            <div class="card-body d-grid gap-2">

                <a href="<?= App::url('/admin/products/create') ?>" class="btn btn-dark">
                    <i class="bi bi-plus-circle"></i>
                    Nuevo producto
                </a>

                <a href="<?= App::url('/admin/products') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-box"></i>
                    Gestionar productos
                </a>

                <a href="<?= App::url('/admin/sales') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-cart"></i>
                    Ver ventas
                </a>

                <a href="<?= App::url('/admin/users') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-people"></i>
                    Administrar usuarios
                </a>

                <a href="<?= App::url('/admin/accounts') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-person-vcard"></i>
                    Administrar cuentas
                </a>

                <a href="<?= App::url('/admin/reports') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-bar-chart-line"></i>
                    Ver reportes
                </a>

                <a href="<?= App::url('/admin/inventory') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-boxes"></i>
                    Revisar inventario
                </a>

            </div>

        </div>

    </div>

</div>
