<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="<?= App::url('/products') ?>" class="btn btn-outline-secondary">Volver al catalogo</a>
        <a href="<?= App::url('/cart') ?>" class="btn btn-outline-dark">Carrito (<?= (int)($cartCount ?? 0) ?>)</a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php
    $imagen = !empty($preset['imagen'])
        ? $preset['imagen']
        : (!empty($producto['URL_IMAGE']) ? $producto['URL_IMAGE'] : 'https://loremflickr.com/1200/800/technology');

    $titulo = $preset['titulo'] ?? $producto['NOMBRE'];
    $precio = number_format((float)$producto['PRECIO'], 0, ',', '.');
    $existencias = 3;
    $detalles = $preset['detalles'] ?? ($preset['especificaciones'] ?? []);
    ?>

    <section class="card border-0 shadow-sm overflow-hidden">
        <div class="row g-0">
            <div class="col-12 col-lg-6 bg-light">
                <img src="<?= htmlspecialchars($imagen) ?>"
                    alt="<?= htmlspecialchars($titulo) ?>"
                    class="w-100 h-100"
                    style="object-fit: cover; min-height: 320px;">
            </div>

            <div class="col-12 col-lg-6">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        <span class="badge text-bg-primary"><?= htmlspecialchars($producto['CATEGORIA']) ?></span>
                        <span class="badge text-bg-light border"><?= htmlspecialchars($producto['MARCA']) ?></span>
                    </div>

                    <h1 class="h3 fw-bold mb-3"><?= htmlspecialchars($titulo) ?></h1>

                    <?php if (!empty($detalles)): ?>
                        <ul class="mb-4">
                            <?php foreach ($detalles as $linea): ?>
                                <li><?= htmlspecialchars($linea) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($producto['DESCRIPCION'])) ?></p>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                        <div class="fs-2 fw-bold text-danger">&#8353; <?= htmlspecialchars($precio) ?></div>
                        <div class="text-success fw-semibold">Existencias: <?= (int)$existencias ?></div>
                    </div>

                    <div class="border rounded-3 p-3 mb-3" style="background: #f3f3f3;">
                        <div class="d-flex align-items-center flex-wrap gap-2 small">
                            <span class="text-muted me-1">Cuotas con:</span>
                            <span class="badge text-bg-light border">Tasa Cero BAC</span>
                            <span class="badge text-bg-light border">Minicuotas BAC</span>
                            <span class="badge text-bg-light border">Credix</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex align-items-center flex-wrap gap-2 small">
                            <span class="text-muted me-1">Formas de pago:</span>
                            <span class="badge text-bg-light border">VISA</span>
                            <span class="badge text-bg-light border">Mastercard</span>
                            <span class="badge text-bg-light border">AMEX</span>
                            <span class="badge text-bg-light border">SINPE</span>
                            <span class="badge text-bg-light border">Zunify</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger w-100 mb-3">Ver Disponibilidad en Tiendas</button>

                    <?php if (!empty($preset['cuotas'])): ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="fw-bold mb-2">Cuotas Cero Intereses</div>
                            <ul class="mb-0">
                                <?php foreach ($preset['cuotas'] as $cuota): ?>
                                    <li><?= htmlspecialchars($cuota) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= App::url('/cart/add') ?>" method="post" class="row g-2 align-items-end mb-3">
                        <input type="hidden" name="id_producto" value="<?= (int)$producto['ID_PRODUCTO'] ?>">

                        <div class="col-12 col-sm-4">
                            <label class="form-label" for="cantidad">Cantidad</label>
                            <input type="number" min="1" value="1" class="form-control" id="cantidad" name="cantidad">
                        </div>

                        <div class="col-12 col-sm-8 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Anadir al carrito</button>
                        </div>
                    </form>

                    <?php if (!empty($preset['sku']) || !empty($preset['categorias'])): ?>
                        <div class="small text-muted">
                            <?php if (!empty($preset['sku'])): ?><div>SKU: <?= htmlspecialchars($preset['sku']) ?></div><?php endif; ?>
                            <?php if (!empty($preset['categorias'])): ?><div>Categorias: <?= htmlspecialchars($preset['categorias']) ?></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($preset['descripcion_larga'])): ?>
        <section class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3">Descripcion</h2>
                <?php foreach ($preset['descripcion_larga'] as $p): ?>
                    <p class="text-muted mb-3"><?= htmlspecialchars($p) ?></p>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>