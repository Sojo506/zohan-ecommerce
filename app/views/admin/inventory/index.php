<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Inventario</h4>
            <small class="text-muted">Control de stock de productos</small>
        </div>

        <a href="<?= App::url('/admin/inventory/movement') ?>" class="btn btn-dark">
            <i class="bi bi-arrow-left-right"></i> Nuevo movimiento
        </a>

    </div>

    <div class="admin-panel-card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>SKU</th>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Stock mínimo</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($inventory as $i): ?>

                        <?php
                        $lowStock = $i['STOCK'] <= $i['STOCK_MINIMO'];
                        ?>

                        <tr>

                            <td>
                                <span class="badge bg-dark">
                                    <?= htmlspecialchars($i['SKU']) ?>
                                </span>
                            </td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($i['NOMBRE']) ?>
                            </td>

                            <td>

                                <?php if ($lowStock): ?>

                                    <span class="badge bg-danger">
                                        <?= $i['STOCK'] ?>
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-success">
                                        <?= $i['STOCK'] ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-muted">
                                <?= $i['STOCK_MINIMO'] ?>
                            </td>

                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>