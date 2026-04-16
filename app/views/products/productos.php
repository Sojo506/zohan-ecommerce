<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Shop</h1>
            <p class="text-muted mb-0">Explora, filtra y agrega al carrito.</p>
        </div>
        <a href="<?= App::url('/cart') ?>" class="btn btn-outline-dark">
            Carrito (<?= (int)($cartCount ?? 0) ?>)
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <section class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="productSearchForm" action="<?= App::url('/shop') ?>" method="get" class="row g-3 align-items-end">
                <input type="hidden" name="url" value="/shop">

                <div class="col-12 col-md-5">
                    <label for="q" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="q" name="q"
                        value="<?= htmlspecialchars($filtros['q'] ?? '') ?>"
                        placeholder="Nombre o descripcion">
                </div>

                <div class="col-12 col-md-3">
                    <label for="category" class="form-label">Categoria</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Todas</option>
                        <?php $categoriaSeleccionada = str_replace(' ', '', strtolower(trim((string)($filtros['categoria'] ?? '')))); ?>
                        <?php foreach (($categorias ?? []) as $categoria): ?>
                            <?php $nombreCategoria = (string)$categoria['NOMBRE']; ?>
                            <?php $nombreKey = str_replace(' ', '', strtolower(trim($nombreCategoria))); ?>
                            <option value="<?= htmlspecialchars($nombreCategoria) ?>"
                                <?= ($categoriaSeleccionada !== '' && $categoriaSeleccionada === $nombreKey) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombreCategoria) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label for="brand" class="form-label">Marca</label>
                    <select id="brand" name="brand" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach (($marcas ?? []) as $marca): ?>
                            <?php $idMarca = (string)$marca['ID_MARCA']; ?>
                            <option value="<?= htmlspecialchars($idMarca) ?>"
                                <?= (($filtros['marca'] ?? '') === $idMarca) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($marca['NOMBRE']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-grid">
                    <button class="btn btn-primary" type="submit">Filtrar</button>
                </div>
            </form>
        </div>
    </section>


    <section class="border rounded-3 p-3 mb-4" style="background: #f3f3f3;">
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
    </section>
    <section id="resultados">
        <?php if (empty($productos)): ?>
            <div class="alert alert-info">No hay productos con los filtros seleccionados.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($productos as $producto): ?>
                    <?php
                    $imagen = !empty($producto['URL_IMAGE'])
                        ? $producto['URL_IMAGE']
                        : 'https://loremflickr.com/700/450/technology?lock=' . (int)$producto['ID_PRODUCTO'];
                    $discount = (float)($producto['DESCUENTO'] ?? 0);
                    $precioOriginal = (float)$producto['PRECIO'];
                    $precioPromo = $discount > 0 ? $precioOriginal * (1 - ($discount / 100)) : $precioOriginal;
                    $ratingCountValue = (int)($producto['TOTAL_COMENTARIOS'] ?? 0);
                    $ratingValue = $ratingCountValue > 0 ? (float)($producto['CALIFICACION_PROMEDIO'] ?? 0) : 0.0;
                    $ratingText = number_format($ratingValue, 1);
                    $ratingCount = number_format($ratingCountValue);
                    $ratingRounded = max(0, min(5, (int)round($ratingValue)));
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="<?= App::url('/shop/product?id=' . (int)$producto['ID_PRODUCTO']) ?>" class="text-decoration-none text-dark">
                                <img src="<?= htmlspecialchars($imagen) ?>"
                                    class="card-img-top"
                                    alt="<?= htmlspecialchars($producto['NOMBRE']) ?>"
                                    style="height: 220px; object-fit: cover;">
                            </a>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                    <span class="badge text-bg-primary"><?= htmlspecialchars($producto['CATEGORIA']) ?></span>
                                    <span class="badge text-bg-light border"><?= htmlspecialchars($producto['MARCA']) ?></span>
                                    <?php if ($discount > 0): ?>
                                        <span class="badge text-bg-danger"><?= (int)$discount ?>% off</span>
                                    <?php endif; ?>
                                </div>

                                <div class="rating-row mb-2">
                                    <span class="rating-value"><?= $ratingText ?></span>
                                    <span class="rating-stars" aria-hidden="true">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="<?= $i <= $ratingRounded ? 'star-filled' : 'star-empty' ?>">&#9733;</span>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="rating-count">(<?= $ratingCount ?>)</span>
                                </div>

                                <h5 class="card-title">
                                    <a href="<?= App::url('/shop/product?id=' . (int)$producto['ID_PRODUCTO']) ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($producto['NOMBRE']) ?>
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1">
                                    <?= htmlspecialchars(strlen((string)$producto['DESCRIPCION']) > 130 ? substr((string)$producto['DESCRIPCION'], 0, 127) . '...' : (string)$producto['DESCRIPCION']) ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-3 mb-1 flex-wrap gap-2">
                                    <span class="fw-bold fs-5 text-danger">&#36; <?= number_format($precioPromo, 0, ',', '.') ?></span>
                                    <?php if ($discount > 0): ?>
                                        <span class="text-muted text-decoration-line-through small">&#36; <?= number_format($precioOriginal, 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-success small fw-semibold mb-3">Existencias: <?= (int)($producto['STOCK'] ?? 0) ?></div>

                                <div class="d-flex gap-2">
                                    <a href="<?= App::url('/shop/product?id=' . (int)$producto['ID_PRODUCTO']) ?>" class="btn btn-outline-dark w-50">
                                        Ver
                                    </a>

                                    <form action="<?= App::url('/cart/add') ?>" method="post" class="w-50">
                                        <input type="hidden" name="id_producto" value="<?= (int)$producto['ID_PRODUCTO'] ?>">
                                        <button type="submit" class="btn btn-primary w-100">Agregar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>





