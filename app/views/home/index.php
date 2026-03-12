<main>

    <!-- HERO -->
    <section class="bg-light border-bottom">
        <div class="container py-4 py-md-5">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-6">
                    <h1 class="hero-title fw-bold lh-sm mb-3">
                        Compra tecnologia con confianza:
                        <span class="text-primary">rapido</span>,
                        <span class="text-success">seguro</span> y
                        <span class="text-danger">sin vueltas</span>.
                    </h1>

                    <p class="lead text-muted mb-4 hero-subtitle">
                        Laptops, componentes, perifericos y gadgets. Encuentra lo que necesitas con filtros, promos y pagos protegidos.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4 small text-muted">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-success rounded-circle p-2"></span> Envios rapidos
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-primary rounded-circle p-2"></span> Pagos seguros
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-warning rounded-circle p-2"></span> Garantia y soporte
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden hero-card">
                        <div class="ratio ratio-16x9 bg-dark hero-banner">
                            <img src="<?= App::url('/public/images/banner_zohan.png') ?>"
                                alt="Banner Zohan Tech Store"
                                class="w-100 h-100 object-fit-cover">
                        </div>

                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="fw-bold">Promos</div>
                                        <div class="text-muted small">Descuentos semanales</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="fw-bold">Novedades</div>
                                        <div class="text-muted small">Lo ultimo en stock</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-grid">
                                <a href="<?= App::url('/products') ?>" class="btn btn-outline-primary">
                                    Ver ofertas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CATEGORIES -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Compra por categoria</h2>
                    <p class="text-muted mb-0">Encuentra rapido lo que buscas.</p>
                </div>
                <a href="<?= App::url('/products') ?>" class="btn btn-outline-dark">Ver todo</a>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= App::url('/products?category=laptops') ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="fs-2 mb-2"></div>
                                <h5 class="fw-bold mb-1">Laptops</h5>
                                <p class="text-muted mb-0">Estudio, trabajo y gaming.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= App::url('/products?category=components') ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="fs-2 mb-2"></div>
                                <h5 class="fw-bold mb-1">Componentes</h5>
                                <p class="text-muted mb-0">GPU, RAM, SSD, PSU.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= App::url('/products?category=gaming') ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="fs-2 mb-2"></div>
                                <h5 class="fw-bold mb-1">Gaming</h5>
                                <p class="text-muted mb-0">Perifricos y setups.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= App::url('/products?category=accessories') ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="fs-2 mb-2"></div>
                                <h5 class="fw-bold mb-1">Accesorios</h5>
                                <p class="text-muted mb-0">Audio, cables, hubs.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED / BEST SELLERS -->
    <section class="py-4 py-md-5 bg-light border-top border-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Destacados de la semana</h2>
                    <p class="text-muted mb-0">Productos populares y bien valorados.</p>
                </div>
                <a href="<?= App::url('/products') ?>" class="btn btn-primary">Ir al catalogo</a>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach (($featuredProducts ?? []) as $producto): ?>
                    <?php
                    $imagen = !empty($producto['URL_IMAGE'])
                        ? $producto['URL_IMAGE']
                        : 'https://loremflickr.com/700/700/technology?lock=' . (int)$producto['ID_PRODUCTO'];
                    ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="ratio ratio-1x1 bg-white border-bottom">
                                <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($producto['NOMBRE']) ?>" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($producto['NOMBRE']) ?></h6>
                                <p class="text-muted small mb-2"><?= htmlspecialchars(strlen((string)$producto['DESCRIPCION']) > 35 ? substr((string)$producto['DESCRIPCION'], 0, 35) . '...' : (string)$producto['DESCRIPCION']) ?></p>
                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold">$ <?= number_format((float)$producto['PRECIO'], 0, ',', '.') ?></span>
                                    <a href="<?= App::url('/product?id=' . (int)$producto['ID_PRODUCTO']) ?>" class="btn btn-sm btn-outline-dark">Ver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= App::url('/products') ?>" class="btn btn-outline-secondary btn-lg px-4">
                    Ver mas productos
                </a>
            </div>
        </div>
    </section>

    <!-- WHY ZOHAN -->
    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-6">
                    <h2 class="fw-bold mb-3">Por que comprar en Zohan?</h2>
                    <p class="text-muted mb-4">
                        Hacemos que comprar tecnologia sea simple: catalogo claro, compra rapida, y procesos seguros.
                    </p>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="p-4 border rounded-4 h-100">
                                <div class="fw-bold mb-1"> Seguridad</div>
                                <div class="text-muted small">Autenticacion y proteccion de sesiones.</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-4 border rounded-4 h-100">
                                <div class="fw-bold mb-1"> Transparencia</div>
                                <div class="text-muted small">Precios claros y detalle del producto.</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-4 border rounded-4 h-100">
                                <div class="fw-bold mb-1"> Entrega</div>
                                <div class="text-muted small">Logistica eficiente y seguimiento.</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-4 border rounded-4 h-100">
                                <div class="fw-bold mb-1"> Soporte</div>
                                <div class="text-muted small">Asistencia antes y despues de comprar.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-2">Metricas (placeholder)</h5>
                            <p class="text-muted mb-4">Puedes conectarlo a datos reales despues.</p>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4">
                                        <div class="fw-bold fs-4">+1,200</div>
                                        <div class="text-muted small">Productos</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4">
                                        <div class="fw-bold fs-4">4.8/5</div>
                                        <div class="text-muted small">Valoracion</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4">
                                        <div class="fw-bold fs-4">24h</div>
                                        <div class="text-muted small">Despacho</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4">
                                        <div class="fw-bold fs-4">100%</div>
                                        <div class="text-muted small">Pagos seguros</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <a href="<?= App::url('/products') ?>" class="btn btn-primary btn-lg">
                                    Explorar ahora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-4 py-md-5 bg-light border-top">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-1">Lo que dicen los clientes</h2>
                <p class="text-muted mb-0">Opiniones reales (placeholder).</p>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="fw-bold">Excelente servicio</div>
                            <p class="text-muted mt-2 mb-3">
                                Me lleg rapido, todo bien empacado y el producto era exactamente lo que decia.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="small text-muted">Cliente #1</span>
                                <span class="badge text-bg-warning">*****</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="fw-bold">Proceso super facil</div>
                            <p class="text-muted mt-2 mb-3">
                                Filtre por precio y encontre justo lo que necesitaba. El checkout es rapido.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="small text-muted">Cliente #2</span>
                                <span class="badge text-bg-warning">*****</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="fw-bold">Buen soporte</div>
                            <p class="text-muted mt-2 mb-3">
                                Tenia dudas con compatibilidad y me orientaron perfecto antes de comprar.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="small text-muted">Cliente #3</span>
                                <span class="badge text-bg-warning">*****</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-4 py-md-5 bg-light">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Preguntas frecuentes</h2>
                <p class="text-muted">
                    Resolvemos las dudas mas comunes antes de realizar una compra.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="accordion accordion-flush shadow-sm rounded-4 bg-white p-2 p-md-3" id="faqAccordion">

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Que metodos de pago aceptan?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Actualmente aceptamos pagos a traves de <strong>PayPal</strong>, lo que permite utilizar tarjetas de credito o debito de forma segura.
                                    Todos los pagos estan protegidos mediante cifrado y sistemas de seguridad modernos.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Realizan envios a todo el pais?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    S. Realizamos envos a todo el pais mediante empresas de logstica confiables.
                                    El tiempo de entrega depende de la ubicacion, pero generalmente tarda entre
                                    <strong>1 y 3 dias habiles</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Los productos tienen garantia?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    S. Todos nuestros productos cuentan con garantia del fabricante.
                                    El tiempo de garanta puede variar dependiendo del producto,
                                    pero normalmente es entre <strong>6 y 12 meses</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Puedo devolver un producto?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    S. Si el producto presenta defectos o problemas de funcionamiento,
                                    puedes solicitar un reemplazo o devolucion dentro de los
                                    <strong>7 dias posteriores a la compra</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Necesito una cuenta para comprar?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    S. Crear una cuenta te permite realizar compras, guardar tu historial
                                    de pedidos y gestionar tu informacin de envo de manera mas rpida.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <h5 class="fw-bold">No encontraste tu respuesta?</h5>
                <p class="text-muted">
                    Nuestro equipo puede ayudarte con cualquier consulta sobre productos o pedidos.
                </p>

                <a href="<?= App::url('/contact') ?>" class="btn btn-primary px-4">
                    Contactar soporte
                </a>
            </div>

        </div>
    </section>

</main>



