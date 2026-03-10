<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">

        <h4>Inventario</h4>

        <a href="<?= App::url('/admin/inventory/movement') ?>" class="btn btn-dark">
            Nuevo movimiento
        </a>

    </div>

    <table class="table">

        <thead>

            <tr>
                <th>SKU</th>
                <th>Producto</th>
                <th>Stock</th>
                <th>Stock mínimo</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($inventory as $i): ?>

                <tr>

                    <td><?= $i['SKU'] ?></td>

                    <td><?= $i['NOMBRE'] ?></td>

                    <td><?= $i['STOCK'] ?></td>

                    <td><?= $i['STOCK_MINIMO'] ?></td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>