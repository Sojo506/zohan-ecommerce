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

    $imagenes = $imagenes ?? [];
    $imagenes = array_values(array_filter($imagenes, static function ($url) {
        return is_string($url) && trim($url) !== '';
    }));

    if (empty($imagenes)) {
        $imagenes = [$imagen];
    }

    $imagenPrincipal = $imagenes[0] ?? $imagen;

    $titulo = $preset['titulo'] ?? $producto['NOMBRE'];
    $precio = number_format((float)$producto['PRECIO'], 0, ',', '.');
    $existencias = (int)($existencias ?? 0);
    $detalles = $preset['detalles'] ?? ($preset['especificaciones'] ?? []);
    ?>

    <section class="card border-0 shadow-sm overflow-hidden">
        <div class="row g-0">
            <div class="col-12 col-lg-6 bg-light">
                <div class="p-3">
                    <div class="ratio ratio-4x3 bg-white rounded-3 overflow-hidden">
                        <img id="productMainImage" src="<?= htmlspecialchars($imagenPrincipal) ?>"
                            alt="<?= htmlspecialchars($titulo) ?>"
                            class="w-100 h-100 object-fit-cover">
                    </div>

                    <?php if (count($imagenes) > 1): ?>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <?php foreach ($imagenes as $index => $imagenUrl): ?>
                                <button type="button"
                                    class="btn p-0 border rounded-3 product-thumb <?= $index === 0 ? 'is-active' : '' ?>"
                                    data-product-thumb="<?= htmlspecialchars($imagenUrl) ?>"
                                    aria-label="Ver imagen <?= (int)$index + 1 ?>">
                                    <img src="<?= htmlspecialchars($imagenUrl) ?>"
                                        alt="<?= htmlspecialchars($titulo) ?> miniatura <?= (int)$index + 1 ?>"
                                        width="72" height="56"
                                        style="object-fit: cover; border-radius: 6px;">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
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

                    <button type="button" class="btn btn-danger w-100 mb-3" data-bs-toggle="collapse" data-bs-target="#storeAvailability" aria-expanded="false" aria-controls="storeAvailability">Ver Disponibilidad en Tiendas</button>

                    <div class="collapse" id="storeAvailability">
                        <div class="availability-panel">
                            <div class="availability-header">Disponibilidad en Tiendas</div>
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <div class="availability-card">
                                        <div class="availability-title">ZONA GAM:</div>
                                        <div class="availability-list">
                                            <div class="availability-item">
                                                <div class="availability-location">Escaz&uacute;</div>
                                                <div class="availability-status-text availability-status--low">Queda 1</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">San Jos&eacute; Centro</div>
                                                <div class="availability-status-text availability-status--low">Queda 1</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Alajuela</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Cartago</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Desamparados</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Heredia</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">La Valencia (Heredia)</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Lindora</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Plaza San Francisco (Heredia)</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">San Pedro</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Tib&aacute;s</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="availability-card">
                                        <div class="availability-title">FUERA DE GAM:</div>
                                        <div class="availability-list">
                                            <div class="availability-item">
                                                <div class="availability-location">Gu&aacute;piles</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">Liberia</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">P&eacute;rez Zeled&oacute;n C- Town</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">P&eacute;rez Zeled&oacute;n Centro</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">San Carlos</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                            <div class="availability-item">
                                                <div class="availability-location">San Ram&oacute;n</div>
                                                <div class="availability-status-text availability-status--none">No disponible</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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
                            <input type="number" min="1" max="<?= max(1, (int)$existencias) ?>" value="1" class="form-control" id="cantidad" name="cantidad">
                        </div>

                        <div class="col-12 col-sm-8 d-grid">
                            <?php if ($existencias > 0): ?>
                                <button type="submit" class="btn btn-primary btn-lg">Anadir al carrito</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary btn-lg" disabled>Sin existencias</button>
                            <?php endif; ?>
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






