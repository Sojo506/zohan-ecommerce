<main>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Tu carrito</h1>
            <p class="text-muted mb-0">Gestiona tus productos antes de comprar.</p>
        </div>
        <a href="<?= App::url('/products') ?>" class="btn btn-outline-primary">Agregar m&aacute;s productos</a>
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
            Tu carrito est&aacute; vac&iacute;o. <a href="<?= App::url('/products') ?>">Ir al cat&aacute;logo</a>
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
                                : 'https://loremflickr.com/200/150/technology?lock=' . (int) $producto['ID_PRODUCTO'];
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= htmlspecialchars($imagen) ?>"
                                            alt="<?= htmlspecialchars($producto['NOMBRE']) ?>" width="72" height="56"
                                            style="object-fit: cover; border-radius: 8px;">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($producto['NOMBRE']) ?></div>
                                            <a href="<?= App::url('/product?id=' . (int) $producto['ID_PRODUCTO']) ?>"
                                                class="small">Ver detalle</a>
                                        </div>
                                    </div>
                                </td>
                                <td>&#8353; <?= number_format((float) $producto['PRECIO'], 0, ',', '.') ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="<?= App::url('/cart/update') ?>" method="post" class="m-0">
                                            <input type="hidden" name="id_producto"
                                                value="<?= (int) $producto['ID_PRODUCTO'] ?>">
                                            <input type="hidden" name="accion" value="restar">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                                        </form>

                                        <span class="fw-semibold"><?= (int) $item['cantidad'] ?></span>

                                        <form action="<?= App::url('/cart/update') ?>" method="post" class="m-0">
                                            <input type="hidden" name="id_producto"
                                                value="<?= (int) $producto['ID_PRODUCTO'] ?>">
                                            <input type="hidden" name="accion" value="sumar">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-semibold">&#8353; <?= number_format((float) $item['subtotal'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <form action="<?= App::url('/cart/remove') ?>" method="post">
                                        <input type="hidden" name="id_producto" value="<?= (int) $producto['ID_PRODUCTO'] ?>">
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
                        <span class="fs-5 fw-bold">&#8353; <?= number_format((float) $total, 0, ',', '.') ?></span>
                    </div>
                    <a href="<?= App::url('/products') ?>" class="btn btn-outline-primary w-100 mb-2">Agregar m&aacute;s
                        productos</a>

                    <?php if (isset($_SESSION['user'])): ?>
                        <div id="paypal-button-container" class="mt-3 w-100"></div>

                        <script
                            src="https://www.paypal.com/sdk/js?client-id=<?= Env::get('PAYPAL_CLIENT_ID') ?>&currency=USD"></script>
                        <script>
                            const totalColones = <?= (float) $total ?>;
                            const tipoCambio = 510;
                            const totalUSD = (totalColones / tipoCambio).toFixed(2);

                            paypal.Buttons({
                                style: { layout: 'vertical', color: 'blue', shape: 'rect', label: 'pay' },

                                createOrder: function (data, actions) {
                                    return actions.order.create({
                                        purchase_units: [{ amount: { value: totalUSD } }]
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
                                                alert('¡' + resultado.message + '!');
                                                window.location.href = '<?= App::url('/profile') ?>';
                                            } else {
                                                alert('Hubo un problema: ' + resultado.message);
                                            }
                                        })
                                        .catch(function (error) {
                                            console.error('Error de comunicación con el backend:', error);
                                            alert('Ocurrió un error inesperado al procesar el pago.');
                                        });
                                },

                                onCancel: function (data) {
                                    console.log("El usuario cerró la ventana de PayPal sin pagar.");
                                },
                                onError: function (err) {
                                    console.error("Error devuelto por el widget de PayPal:", err);
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