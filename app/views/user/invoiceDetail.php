<?php
    $invoice = $invoice ?? [];
    $products = $products ?? [];

    $direccion = trim((string)($invoice['DIRECCION'] ?? ''));
    if ($direccion === '') {
        $direccion = 'No registrada';
    }

    $estado = strtolower((string)($invoice['ESTADO'] ?? ''));
    $badge = 'secondary';
    if (in_array($estado, ['pagado', 'pagada', 'completado', 'completada'], true)) {
        $badge = 'success';
    } elseif ($estado === 'pendiente') {
        $badge = 'warning';
    } elseif (in_array($estado, ['cancelado', 'cancelada'], true)) {
        $badge = 'danger';
    }

    $fecha = !empty($invoice['FECHA_FACTURA'])
        ? date('d M Y H:i', strtotime($invoice['FECHA_FACTURA']))
        : 'N/D';

    $cliente = trim((string)($invoice['NOMBRE'] ?? '') . ' ' . (string)($invoice['APELLIDO_PATERNO'] ?? ''));
    if ($cliente === '') {
        $cliente = 'Cliente';
    }

    $subtotal = (float)($invoice['SUBTOTAL'] ?? 0);
    $iva = (float)($invoice['IMPUESTO'] ?? 0);
    $total = (float)($invoice['TOTAL'] ?? 0);
    $descuento = max(0, ($subtotal + $iva) - $total);
?>

<main data-invoice-id="<?= (int)($invoice['ID_FACTURA'] ?? 0) ?>"
    data-comment-url="<?= App::url('/invoiceDetail/comment') ?>">
    <section class="mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between gap-3">
                    <div>
                        <h1 class="h4 mb-1">Factura #<?= (int)($invoice['ID_FACTURA'] ?? 0) ?></h1>
                        <div class="text-muted small">Fecha: <?= htmlspecialchars($fecha) ?></div>
                        <div class="text-muted small">Dirección: <?= htmlspecialchars($direccion) ?></div>
                    </div>
                    <div class="text-end">
                        <div class="mb-1">
                            <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars((string)($invoice['ESTADO'] ?? 'N/D')) ?></span>
                        </div>
                        <div class="small text-muted">Cliente: <?= htmlspecialchars($cliente) ?></div>
                        <?php if (!empty($invoice['APELLIDO_MATERNO'])): ?>
                            <div class="small text-muted"><?= htmlspecialchars((string)$invoice['APELLIDO_MATERNO']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-12 col-lg-8">
            <h2 class="h5 mb-3">Productos comprados</h2>

            <?php if (empty($products)): ?>
                <div class="alert alert-info">No hay productos registrados para esta factura.</div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $imagen = !empty($product['URL_IMAGE'])
                        ? $product['URL_IMAGE']
                        : 'https://loremflickr.com/240/200/technology?lock=' . (int)$product['ID_PRODUCTO'];
                    ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="row g-0 align-items-stretch">
                            <div class="col-4 col-md-3">
                                <img src="<?= htmlspecialchars($imagen) ?>"
                                    alt="<?= htmlspecialchars((string)$product['NOMBRE']) ?>"
                                    class="w-100 h-100 object-fit-cover"
                                    style="min-height: 120px;">
                            </div>
                            <div class="col-8 col-md-9">
                                <div class="card-body">
                                    <h3 class="h6 fw-bold mb-2"><?= htmlspecialchars((string)$product['NOMBRE']) ?></h3>
                                    <div class="small text-muted">Precio unitario: $ <?= number_format((float)$product['PRECIO_UNITARIO'], 2) ?></div>
                                    <div class="small text-muted">Cantidad: <?= (int)$product['CANTIDAD'] ?></div>
                                    <div class="small fw-semibold mb-2">Subtotal: $ <?= number_format((float)$product['SUBTOTAL_LINEA'], 2) ?></div>

                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm js-toggle-comment"
                                        data-product-id="<?= (int)$product['ID_PRODUCTO'] ?>">
                                        Comentar producto
                                    </button>

                                    <div class="comment-box mt-3 d-none"
                                        data-comment-box="<?= (int)$product['ID_PRODUCTO'] ?>">
                                        <label class="form-label small mb-1">Comentario</label>
                                        <textarea class="form-control js-comment-text"
                                            rows="3"
                                            placeholder="Escribe tu comentario..."></textarea>
                                        <div class="mt-2">
                                            <div class="small text-muted mb-1">Calificación</div>
                                            <div class="rating-group" role="group" aria-label="Calificación">
                                                <button type="button" class="btn btn-outline-secondary btn-sm rating-pill is-active" data-rating="5">5</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm rating-pill" data-rating="4">4</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm rating-pill" data-rating="3">3</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm rating-pill" data-rating="2">2</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm rating-pill" data-rating="1">1</button>
                                            </div>
                                            <input type="hidden" class="js-rating-value" value="5">
                                            <div class="small text-muted mt-1">
                                                Seleccionado: <span class="js-rating-label">5 - Excelente</span>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-primary btn-sm mt-2 js-send-comment"
                                            data-product-id="<?= (int)$product['ID_PRODUCTO'] ?>">
                                            Enviar comentario
                                        </button>
                                        <div class="small mt-2 d-none js-comment-status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Resumen</h2>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>$ <?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">IVA</span>
                        <span>$ <?= number_format($iva, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Descuentos</span>
                        <span>$ <?= number_format($descuento, 2) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-success">$ <?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-4 mb-4 d-flex flex-wrap gap-2 justify-content-center">
        <a href="<?= App::url('/profile') ?>" class="btn btn-outline-dark">Volver al perfil</a>
    </section>
</main>
