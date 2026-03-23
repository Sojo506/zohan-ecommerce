<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Tu carrito</h1>
            <p class="text-muted mb-0">Gestiona tus productos antes de comprar.</p>
        </div>
        <a href="<?= App::url('/tienda') ?>" class="btn btn-outline-primary">Agregar m&aacute;s productos</a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">
            Tu carrito est&aacute; vac&iacute;o. <a href="<?= App::url('/tienda') ?>">Ir al cat&aacute;logo</a>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $producto = $item['producto'];
                            $imagen = !empty($producto['URL_IMAGE'])
                                ? $producto['URL_IMAGE']
                                : 'https://loremflickr.com/200/150/technology?lock=' . (int)$producto['ID_PRODUCTO'];
                            $descuento = (float)($producto['DESCUENTO'] ?? 0);
                            $precioOriginal = (float)$producto['PRECIO'];
                            $precioFinal = $descuento > 0 ? $precioOriginal * (1 - ($descuento / 100)) : $precioOriginal;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= htmlspecialchars($imagen) ?>"
                                            alt="<?= htmlspecialchars($producto['NOMBRE']) ?>"
                                            width="72" height="56"
                                            style="object-fit: cover; border-radius: 8px;">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($producto['NOMBRE']) ?></div>
                                            <a href="<?= App::url('/tienda/product?id=' . (int)$producto['ID_PRODUCTO']) ?>" class="small">Ver detalle</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">&#8353; <?= number_format($precioFinal, 0, ',', '.') ?></div>
                                    <?php if ($descuento > 0): ?>
                                        <div class="small text-muted text-decoration-line-through">&#8353; <?= number_format($precioOriginal, 0, ',', '.') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="<?= App::url('/cart/update') ?>" method="post" class="m-0">
                                            <input type="hidden" name="id_producto" value="<?= (int)$producto['ID_PRODUCTO'] ?>">
                                            <input type="hidden" name="accion" value="restar">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                                        </form>

                                        <span class="fw-semibold"><?= (int)$item['cantidad'] ?></span>

                                        <form action="<?= App::url('/cart/update') ?>" method="post" class="m-0">
                                            <input type="hidden" name="id_producto" value="<?= (int)$producto['ID_PRODUCTO'] ?>">
                                            <input type="hidden" name="accion" value="sumar">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-semibold">&#8353; <?= number_format((float)$item['subtotal'], 0, ',', '.') ?></td>
                                <td>
                                    <form action="<?= App::url('/cart/remove') ?>" method="post">
                                        <input type="hidden" name="id_producto" value="<?= (int)$producto['ID_PRODUCTO'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <div class="card border-0 shadow-sm" style="min-width: 280px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total:</span>
                        <span class="fs-5 fw-bold">&#8353; <?= number_format((float)$total, 0, ',', '.') ?></span>
                    </div>
                    <a href="<?= App::url('/tienda') ?>" class="btn btn-outline-primary w-100 mb-2">Agregar m&aacute;s productos</a>
                    <button class="btn btn-primary w-100" type="button" disabled>Proceder al pago (pr&oacute;ximo paso)</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>


