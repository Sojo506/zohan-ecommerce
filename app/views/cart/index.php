<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Tu carrito</h1>
            <p class="text-muted mb-0">Gestiona tus productos antes de comprar.</p>
        </div>
        <a href="<?= App::url('/tienda') ?>" class="btn btn-outline-primary">Agregar m&aacute;s productos</a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    text: <?= json_encode($_SESSION['flash_success']) ?>,
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
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
                            $precioFinal = $descuento > 0
                                ? $precioOriginal * (1 - ($descuento / 100))
                                : $precioOriginal;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= htmlspecialchars($imagen) ?>"
                                            alt="<?= htmlspecialchars($producto['NOMBRE']) ?>"
                                            width="72"
                                            height="56"
                                            style="object-fit: cover; border-radius: 8px;">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($producto['NOMBRE']) ?></div>
                                            <a href="<?= App::url('/tienda/product?id=' . (int)$producto['ID_PRODUCTO']) ?>"
                                                class="small">Ver detalle</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">&#36; <?= number_format($precioFinal, 0, ',', '.') ?></div>
                                    <?php if ($descuento > 0): ?>
                                        <div class="small text-muted text-decoration-line-through">
                                            &#36; <?= number_format($precioOriginal, 0, ',', '.') ?>
                                        </div>
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
                                <td class="fw-semibold">
                                    &#36; <?= number_format((float)$item['subtotal'], 0, ',', '.') ?>
                                </td>
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
            <div class="card border-0 shadow-sm" style="min-width: 320px;">
                <div class="card-body">

                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="mb-3">
                            <label for="coupon-code" class="form-label fw-semibold small text-muted">Cupón de descuento</label>
                            <div class="input-group">
                                <input type="text" id="coupon-code" class="form-control" placeholder="Ej: DESCUENTO10"
                                    maxlength="50" <?= isset($_SESSION['coupon']) ? 'disabled' : '' ?>
                                    value="<?= htmlspecialchars($_SESSION['coupon']['code'] ?? '') ?>">
                                <button type="button" id="btn-apply-coupon" class="btn btn-outline-secondary"
                                    <?= isset($_SESSION['coupon']) ? 'style="display:none"' : '' ?>>
                                    Aplicar
                                </button>
                                <button type="button" id="btn-remove-coupon" class="btn btn-outline-danger"
                                    <?= !isset($_SESSION['coupon']) ? 'style="display:none"' : '' ?>>
                                    Quitar
                                </button>
                            </div>
                            <div id="coupon-message" class="small mt-1
                                <?= isset($_SESSION['coupon']) ? 'text-success' : '' ?>">
                                <?php if (isset($_SESSION['coupon'])): ?>
                                    Cupón aplicado: <?= (float)$_SESSION['coupon']['percentage'] ?>% de descuento
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Subtotal:</span>
                        <span id="display-subtotal" class="fw-semibold">&#36; <?= number_format((float)$total, 0, ',', '.') ?></span>
                    </div>

                    <div id="coupon-discount-row" class="d-flex justify-content-between align-items-center mb-1"
                        <?= !isset($_SESSION['coupon']) ? 'style="display:none"' : '' ?>>
                        <span class="text-success small">Descuento (<span id="coupon-percent"><?= (float)($_SESSION['coupon']['percentage'] ?? 0) ?></span>%):</span>
                        <span id="display-discount" class="text-success fw-semibold">
                            <?php if (isset($_SESSION['coupon'])): ?>
                                - &#36; <?= number_format((float)$total * ($_SESSION['coupon']['percentage'] / 100), 0, ',', '.') ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total:</span>
                        <span id="display-total" class="fs-5 fw-bold">
                            <?php
                            $totalFinal = (float)$total;
                            if (isset($_SESSION['coupon'])) {
                                $totalFinal = $totalFinal * (1 - ($_SESSION['coupon']['percentage'] / 100));
                            }
                            ?>
                            &#36; <?= number_format($totalFinal, 0, ',', '.') ?>
                        </span>
                    </div>

                    <a href="<?= App::url('/tienda') ?>" class="btn btn-outline-primary w-100 mb-2">
                        Agregar m&aacute;s productos
                    </a>

                    <?php if (isset($_SESSION['user'])): ?>
                        <div id="paypal-button-container" class="mt-3 w-100"></div>

                        <script src="https://www.paypal.com/sdk/js?client-id=<?= Env::get('PAYPAL_CLIENT_ID') ?>&currency=USD"></script>
                        <script>
                            const totalColones = <?= (float)$total ?>;
                            const tipoCambio = 510;
                            let couponPercent = <?= (float)($_SESSION['coupon']['percentage'] ?? 0) ?>;

                            function getDiscountedTotalUSD() {
                                const discounted = totalColones * (1 - (couponPercent / 100));
                                return (discounted / tipoCambio).toFixed(2);
                            }

                            function formatColones(value) {
                                return '\u20A1 ' + Math.round(value).toLocaleString('es-CR');
                            }

                            function updateDisplayTotals() {
                                const discount = totalColones * (couponPercent / 100);
                                const final_ = totalColones - discount;

                                document.getElementById('display-subtotal').textContent = formatColones(totalColones);
                                document.getElementById('display-total').textContent = formatColones(final_);

                                if (couponPercent > 0) {
                                    document.getElementById('coupon-discount-row').style.display = 'flex';
                                    document.getElementById('coupon-percent').textContent = couponPercent;
                                    document.getElementById('display-discount').textContent = '- ' + formatColones(discount);
                                } else {
                                    document.getElementById('coupon-discount-row').style.display = 'none';
                                }
                            }

                            // Aplicar cupón
                            document.getElementById('btn-apply-coupon').addEventListener('click', function () {
                                const code = document.getElementById('coupon-code').value.trim();
                                if (!code) return;

                                this.disabled = true;
                                this.textContent = '...';

                                fetch('<?= App::url('/api/coupon/validate') ?>', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ code: code })
                                })
                                .then(function (r) { return r.json(); })
                                .then(function (res) {
                                    const msgEl = document.getElementById('coupon-message');

                                    if (res.success) {
                                        couponPercent = res.percentage;
                                        msgEl.className = 'small mt-1 text-success';
                                        msgEl.textContent = res.message;
                                        document.getElementById('coupon-code').disabled = true;
                                        document.getElementById('btn-apply-coupon').style.display = 'none';
                                        document.getElementById('btn-remove-coupon').style.display = '';
                                        updateDisplayTotals();
                                    } else {
                                        couponPercent = 0;
                                        msgEl.className = 'small mt-1 text-danger';
                                        msgEl.textContent = res.message;
                                        updateDisplayTotals();
                                        document.getElementById('btn-apply-coupon').disabled = false;
                                        document.getElementById('btn-apply-coupon').textContent = 'Aplicar';
                                    }
                                })
                                .catch(function () {
                                    document.getElementById('btn-apply-coupon').disabled = false;
                                    document.getElementById('btn-apply-coupon').textContent = 'Aplicar';
                                });
                            });

                            // Quitar cupón
                            document.getElementById('btn-remove-coupon').addEventListener('click', function () {
                                fetch('<?= App::url('/api/coupon/remove') ?>', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' }
                                });

                                couponPercent = 0;
                                document.getElementById('coupon-code').value = '';
                                document.getElementById('coupon-code').disabled = false;
                                document.getElementById('coupon-message').textContent = '';
                                document.getElementById('coupon-message').className = 'small mt-1';
                                document.getElementById('btn-apply-coupon').style.display = '';
                                document.getElementById('btn-apply-coupon').disabled = false;
                                document.getElementById('btn-apply-coupon').textContent = 'Aplicar';
                                document.getElementById('btn-remove-coupon').style.display = 'none';
                                updateDisplayTotals();
                            });

                            paypal.Buttons({
                                style: {
                                    layout: 'vertical',
                                    color: 'blue',
                                    shape: 'rect',
                                    label: 'pay'
                                },

                                createOrder: function (data, actions) {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: getDiscountedTotalUSD()
                                            }
                                        }]
                                    });
                                },

                                onApprove: function (data, actions) {
                                    return fetch('<?= App::url('/api/payment/capture') ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            orderID: data.orderID
                                        })
                                    })
                                    .then(function (response) {
                                        return response.json();
                                    })
                                    .then(function (resultado) {
                                        if (resultado.success) {
                                            return Swal.fire({
                                                icon: 'success',
                                                title: 'Pago procesado',
                                                text: resultado.message,
                                                showDenyButton: true,
                                                confirmButtonText: 'Ir a mi perfil',
                                                denyButtonText: 'Seguir comprando',
                                                allowOutsideClick: false,
                                                allowEscapeKey: false
                                            }).then(function (decision) {
                                                if (decision.isConfirmed) {
                                                    window.location.href = '<?= App::url('/profile') ?>';
                                                    return;
                                                }

                                                if (decision.isDenied) {
                                                    window.location.href = '<?= App::url('/tienda') ?>';
                                                }
                                            });
                                        }

                                        return Swal.fire({
                                            icon: 'error',
                                            title: 'No se pudo procesar el pago',
                                            text: resultado.message || 'Ocurrió un problema al confirmar la compra.',
                                            confirmButtonText: 'Entendido'
                                        });
                                    })
                                    .catch(function () {
                                        return Swal.fire({
                                            icon: 'error',
                                            title: 'Error inesperado',
                                            text: 'Ocurrió un error inesperado al procesar el pago.',
                                            confirmButtonText: 'Entendido'
                                        });
                                    });
                                },

                                onCancel: function () {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Pago cancelado',
                                        text: 'Cerraste la ventana de PayPal antes de completar el pago.',
                                        confirmButtonText: 'Seguir comprando'
                                    });
                                },

                                onError: function () {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error con PayPal',
                                        text: 'PayPal devolvió un error al intentar procesar el pago.',
                                        confirmButtonText: 'Entendido'
                                    });
                                }
                            }).render('#paypal-button-container');
                        </script>
                    <?php else: ?>
                        <a href="<?= App::url('/login') ?>" class="btn btn-primary w-100 mt-2">
                            Inicia sesión para pagar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>