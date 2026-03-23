<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="<?= App::url('/tienda') ?>" class="btn btn-outline-secondary">Volver a la tienda</a>
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
    $imagenFallback = !empty($preset['imagen'])
        ? $preset['imagen']
        : (!empty($producto['URL_IMAGE']) ? $producto['URL_IMAGE'] : 'https://loremflickr.com/1200/800/technology');

    $imagenes = $imagenes ?? [];
    $imagenes = array_values(array_filter($imagenes, static function ($url) {
        return is_string($url) && trim($url) !== '';
    }));

    if (empty($imagenes)) {
        $imagenes = [$imagenFallback];
    }

    $imagenPrincipal = $imagenes[0] ?? $imagenFallback;

    $titulo = $preset['titulo'] ?? $producto['NOMBRE'];
    $precioOriginal = (float)$producto['PRECIO'];
    $discount = (float)($producto['DESCUENTO'] ?? 0);
    $precioPromo = $discount > 0 ? $precioOriginal * (1 - ($discount / 100)) : $precioOriginal;
    $precio = number_format($precioPromo, 0, ',', '.');
    $existencias = (int)($existencias ?? 0);
    $detalles = $preset['detalles'] ?? ($preset['especificaciones'] ?? []);
    $ratingValue = 4.2 + ((int)$producto['ID_PRODUCTO'] % 8) * 0.1;
    $ratingValue = min(4.9, $ratingValue);
    $ratingText = number_format($ratingValue, 1);
    $ratingCount = number_format(2000 + ((int)$producto['ID_PRODUCTO'] * 37) % 25000);
    $ratingRounded = (int)round($ratingValue);
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
                                    data-product-thumb-index="<?= (int)$index ?>"
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

                    <div class="rating-row rating-summary mb-3" data-product-id="<?= (int)$producto['ID_PRODUCTO'] ?>" data-base-rating="<?= $ratingText ?>">
                        <span class="rating-value"><?= $ratingText ?></span>
                        <span class="rating-stars rating-summary-stars" aria-hidden="true">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="<?= $i <= $ratingRounded ? 'star-filled' : 'star-empty' ?>">&#9733;</span>
                            <?php endfor; ?>
                        </span>
                        <span class="rating-count" id="ratingCount-<?= (int)$producto['ID_PRODUCTO'] ?>" data-base-count="<?= $ratingCount ?>">(<?= $ratingCount ?>)</span>
                        <a class="btn btn-link p-0 rating-toggle" href="#reviews-section-<?= (int)$producto['ID_PRODUCTO'] ?>" data-target="reviews-<?= (int)$producto['ID_PRODUCTO'] ?>">Ver opiniones</a>
                    </div>

                    <?php if (!empty($detalles)): ?>
                        <ul class="mb-4">
                            <?php foreach ($detalles as $linea): ?>
                                <li><?= htmlspecialchars($linea) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($producto['DESCRIPCION'])) ?></p>
                    <?php endif; ?>

                    <!-- Seccion de calificacion eliminada por requerimiento -->

                    <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                        <div class="fs-2 fw-bold text-danger">&#8353; <?= htmlspecialchars($precio) ?></div>
                        <?php if ($discount > 0): ?>
                            <div class="text-muted text-decoration-line-through">&#8353; <?= number_format($precioOriginal, 0, ',', '.') ?></div>
                            <span class="badge text-bg-danger"><?= (int)$discount ?>% off</span>
                        <?php endif; ?>
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

<?php
$reviewSamples = [
    ['name' => 'Andrea', 'rating' => 5, 'text' => 'La calidad se siente premium, el empaque llegó perfecto.', 'meta' => 'Color: Negro · Tamaño: Estándar', 'date' => '10 AGO 2025', 'likes' => 4, 'tags' => ['verified', 'bonito', 'calidad']],
    ['name' => 'Carlos', 'rating' => 4, 'text' => 'Cumple con lo que promete, envío rápido y sin problemas.', 'meta' => 'Color: Azul · Versión: V2', 'date' => '08 AGO 2025', 'likes' => 3, 'tags' => ['verified', 'describe', 'costa_rica']],
    ['name' => 'Sofía', 'rating' => 5, 'text' => 'Excelente compra, el rendimiento es top para mi trabajo.', 'meta' => 'Uso: Oficina · Entrega: Rápida', 'date' => '05 AGO 2025', 'likes' => 6, 'tags' => ['verified', 'bonito', 'describe']],
    ['name' => 'Diego', 'rating' => 4, 'text' => 'Buen balance entre precio y calidad, lo recomiendo.', 'meta' => 'Color: Blanco · Garantía: 1 año', 'date' => '01 AGO 2025', 'likes' => 2, 'tags' => ['verified', 'calidad']],
    ['name' => 'Valeria', 'rating' => 5, 'text' => 'Me encantó el acabado, se ve duradero.', 'meta' => 'Material: Premium · Uso: Diario', 'date' => '28 JUL 2025', 'likes' => 5, 'tags' => ['verified', 'bonito']],
    ['name' => 'Andrés', 'rating' => 4, 'text' => 'Funciona perfecto con mi setup, instalación fácil.', 'meta' => 'Setup: Gaming · Compatibilidad: OK', 'date' => '26 JUL 2025', 'likes' => 1, 'tags' => ['verified']],
    ['name' => 'Kimberly', 'rating' => 5, 'text' => 'Superó mis expectativas, muy rápido.', 'meta' => 'Entrega: 48 horas · Empaque: Excelente', 'date' => '22 JUL 2025', 'likes' => 4, 'tags' => ['verified', 'calidad']],
    ['name' => 'José', 'rating' => 3, 'text' => 'Todo bien, aunque esperaba un poco más de accesorios.', 'meta' => 'Accesorios: Básicos · Color: Negro', 'date' => '20 JUL 2025', 'likes' => 1, 'tags' => ['verified', 'describe']],
    ['name' => 'Natalia', 'rating' => 5, 'text' => 'Se siente sólido y el envío llegó antes de tiempo.', 'meta' => 'Compra verificada · Entrega: Anticipada', 'date' => '18 JUL 2025', 'likes' => 7, 'tags' => ['verified', 'costa_rica']],
    ['name' => 'Mario', 'rating' => 4, 'text' => 'Buen producto, el soporte respondió rápido.', 'meta' => 'Soporte: Rápido · Respuesta: 1 día', 'date' => '16 JUL 2025', 'likes' => 2, 'tags' => ['verified']],
    ['name' => 'Paula', 'rating' => 5, 'text' => 'Ideal para estudiar y trabajar, lo uso a diario.', 'meta' => 'Uso: Estudio · Rendimiento: Alto', 'date' => '14 JUL 2025', 'likes' => 3, 'tags' => ['verified', 'bonito']],
    ['name' => 'Bryan', 'rating' => 4, 'text' => 'Buen desempeño en juegos, sin tirones.', 'meta' => 'Gaming: 1080p · Temperatura: Estable', 'date' => '12 JUL 2025', 'likes' => 2, 'tags' => ['verified', 'bonito']],
    ['name' => 'Laura', 'rating' => 5, 'text' => 'Muy buena relación calidad/precio.', 'meta' => 'Precio: Justo · Calidad: Alta', 'date' => '10 JUL 2025', 'likes' => 5, 'tags' => ['verified', 'calidad']],
    ['name' => 'Esteban', 'rating' => 4, 'text' => 'Se calienta un poco pero nada grave.', 'meta' => 'Temperatura: Media · Ruido: Bajo', 'date' => '08 JUL 2025', 'likes' => 1, 'tags' => ['verified', 'describe']],
    ['name' => 'María José', 'rating' => 5, 'text' => 'La batería rinde bastante, muy contenta.', 'meta' => 'Batería: 6h · Uso: Mixto', 'date' => '06 JUL 2025', 'likes' => 4, 'tags' => ['verified', 'calidad']],
    ['name' => 'Jorge', 'rating' => 4, 'text' => 'Conectividad estable, sin cortes.', 'meta' => 'WiFi: Estable · Bluetooth: OK', 'date' => '04 JUL 2025', 'likes' => 2, 'tags' => ['verified']],
    ['name' => 'Ana', 'rating' => 5, 'text' => 'Se ve elegante y funciona de maravilla.', 'meta' => 'Diseño: Elegante · Color: Gris', 'date' => '02 JUL 2025', 'likes' => 3, 'tags' => ['verified', 'bonito']],
    ['name' => 'Luis', 'rating' => 4, 'text' => 'Llegó bien embalado, todo ok.', 'meta' => 'Empaque: Seguro · Entrega: Puntual', 'date' => '30 JUN 2025', 'likes' => 2, 'tags' => ['verified']],
    ['name' => 'Fernanda', 'rating' => 5, 'text' => 'Vale cada colón, lo volvería a comprar.', 'meta' => 'Recompra: Sí · Calidad: Excelente', 'date' => '28 JUN 2025', 'likes' => 6, 'tags' => ['verified', 'calidad']],
    ['name' => 'Kevin', 'rating' => 4, 'text' => 'Buen rendimiento general, recomendado.', 'meta' => 'Rendimiento: Alto · Uso: Diario', 'date' => '26 JUN 2025', 'likes' => 1, 'tags' => ['verified', 'describe']],
];
$reviewMedia = [];
for ($i = 0; $i < 5; $i++) {
    $reviewMedia[] = $imagenes[$i] ?? $imagenPrincipal;
}
$reviewChips = [
    ['label' => 'Todas las valoraciones', 'count' => null, 'active' => true, 'filter' => 'all'],
    ['label' => 'Con fotos', 'count' => 160, 'active' => false, 'filter' => 'photos'],
    ['label' => 'Costa Rica', 'count' => 10, 'active' => false, 'filter' => 'costa_rica'],
    ['label' => 'Como se describe', 'count' => 40, 'active' => false, 'filter' => 'describe'],
    ['label' => 'Bonito y funcional', 'count' => 36, 'active' => false, 'filter' => 'bonito'],
    ['label' => 'Buena calidad', 'count' => 35, 'active' => false, 'filter' => 'calidad'],
];
?>

<section class="rating-reviews mt-4" id="reviews-<?= (int)$producto['ID_PRODUCTO'] ?>">
    <div id="reviews-section-<?= (int)$producto['ID_PRODUCTO'] ?>" tabindex="-1"></div>
    <div class="reviews-summary">
        <div>
            <div class="reviews-title">
                <span class="reviews-title-text">Reseña</span>
                <span class="reviews-score"><?= $ratingText ?></span>
                <span class="rating-stars reviews-stars" aria-hidden="true">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="<?= $i <= $ratingRounded ? 'star-filled' : 'star-empty' ?>">&#9733;</span>
                    <?php endfor; ?>
                </span>
            </div>
            <div class="reviews-count"><?= $ratingCount ?> calificaciones</div>
        </div>
        <div class="reviews-verified">Todo desde compras verificadas</div>
    </div>

    <div class="reviews-sort">
        <span>Ordenar por defecto</span>
        <button type="button" class="reviews-sort-link">Mostrar idioma original</button>
    </div>
    <div class="rating-review-list" data-product-id="<?= (int)$producto['ID_PRODUCTO'] ?>">
        <?php foreach ($reviewSamples as $review): ?>
            <div class="rating-review"
                data-review-tags="<?= htmlspecialchars(implode(' ', $review['tags'] ?? [])) ?>"
                data-review-has-photo="<?= $review['rating'] >= 4 ? '1' : '0' ?>">
                <div class="rating-review-header">
                    <div class="rating-review-user">
                        <div class="rating-review-avatar"><?= htmlspecialchars(strtoupper(substr($review['name'], 0, 1))) ?></div>
                        <div>
                            <div class="rating-review-name"><?= htmlspecialchars($review['name']) ?></div>
                            <?php if (!empty($review['meta'])): ?>
                                <div class="rating-review-meta"><?= htmlspecialchars($review['meta']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="rating-review-rating">
                        <span class="rating-review-stars">
                            <?= str_repeat('&#9733;', (int)$review['rating']) ?><?= str_repeat('&#9734;', 5 - (int)$review['rating']) ?>
                        </span>
                        <span class="rating-review-score"><?= number_format((float)$review['rating'], 1) ?></span>
                    </div>
                </div>
                <div class="rating-review-text"><?= htmlspecialchars($review['text']) ?></div>
                <div class="rating-review-footer">
                    <span class="rating-review-date"><?= htmlspecialchars($review['date'] ?? 'Reciente') ?></span>
                    <span class="rating-review-verified">Compra verificada</span>
                    <span class="rating-review-helpful">Te ha ayudado (<?= (int)($review['likes'] ?? 0) ?>)</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($similares)): ?>
    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h4 fw-bold mb-0">Art&iacute;culos similares</h2>
            <?php if (!empty($producto['CATEGORIA'])): ?>
                <a href="<?= App::url('/tienda?category=' . urlencode((string)$producto['CATEGORIA'])) ?>"
                    class="btn btn-outline-dark btn-sm">Ver m&aacute;s</a>
            <?php endif; ?>
        </div>
        <div class="row g-4">
            <?php foreach ($similares as $item): ?>
                <?php
                $imagenSimilar = !empty($item['URL_IMAGE'])
                    ? $item['URL_IMAGE']
                    : 'https://loremflickr.com/700/450/technology?lock=' . (int)$item['ID_PRODUCTO'];
                $discount = (float)($item['DESCUENTO'] ?? 0);
                $precioOriginal = (float)$item['PRECIO'];
                $precioPromo = $discount > 0 ? $precioOriginal * (1 - ($discount / 100)) : $precioOriginal;
                ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="<?= App::url('/tienda/product?id=' . (int)$item['ID_PRODUCTO']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= htmlspecialchars($imagenSimilar) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($item['NOMBRE']) ?>"
                                style="height: 220px; object-fit: cover;">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex gap-2 mb-2 flex-wrap">
                                <span class="badge text-bg-primary"><?= htmlspecialchars($item['CATEGORIA'] ?? '') ?></span>
                                <span class="badge text-bg-light border"><?= htmlspecialchars($item['MARCA'] ?? '') ?></span>
                                <?php if ($discount > 0): ?>
                                    <span class="badge text-bg-danger"><?= (int)$discount ?>% off</span>
                                <?php endif; ?>
                            </div>
                            <h5 class="card-title">
                                <a href="<?= App::url('/tienda/product?id=' . (int)$item['ID_PRODUCTO']) ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($item['NOMBRE']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?= htmlspecialchars(strlen((string)$item['DESCRIPCION']) > 130 ? substr((string)$item['DESCRIPCION'], 0, 127) . '...' : (string)$item['DESCRIPCION']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3 mb-1 flex-wrap gap-2">
                                <span class="fw-bold fs-5 text-danger">&#8353; <?= number_format($precioPromo, 0, ',', '.') ?></span>
                                <?php if ($discount > 0): ?>
                                    <span class="text-muted text-decoration-line-through small">&#8353; <?= number_format($precioOriginal, 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-success small fw-semibold mb-3">Existencias: <?= (int)($item['STOCK'] ?? 0) ?></div>
                            <div class="d-flex gap-2">
                                <a href="<?= App::url('/tienda/product?id=' . (int)$item['ID_PRODUCTO']) ?>" class="btn btn-outline-dark w-50">
                                    Ver
                                </a>
                                <form action="<?= App::url('/cart/add') ?>" method="post" class="w-50">
                                    <input type="hidden" name="id_producto" value="<?= (int)$item['ID_PRODUCTO'] ?>">
                                    <button type="submit" class="btn btn-primary w-100">Agregar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
</main>