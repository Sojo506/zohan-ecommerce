        </div>

        <footer class="bg-dark text-white mt-0">
                <div class="container py-5">
                        <div class="row g-4">

                                <div class="col-12 col-lg-4">
                                        <h5 class="fw-bold mb-2">Zohan Tech Store</h5>
                                        <p class="text-white-50 mb-3">
                                                Tu tienda de tecnología con compra rápida, pagos seguros y soporte real.
                                        </p>

                                        <div class="d-flex flex-wrap gap-2">
                                                <span class="badge text-bg-secondary">Envío</span>
                                                <span class="badge text-bg-secondary">Soporte</span>
                                                <span class="badge text-bg-secondary">Garantía</span>
                                                <span class="badge text-bg-secondary">PayPal</span>
                                        </div>
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                        <h6 class="fw-bold">Tienda</h6>
                                        <ul class="list-unstyled small">
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda') ?>">Catálogo</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?sort=top') ?>">Top ventas</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?sort=new') ?>">Novedades</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?promo=1') ?>">Ofertas</a></li>
                                        </ul>
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                        <h6 class="fw-bold">Categorías</h6>
                                        <ul class="list-unstyled small">
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?category=laptops') ?>">Laptops</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?category=components') ?>">Componentes</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?category=gaming') ?>">Gaming</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/tienda?category=accessories') ?>">Accesorios</a></li>
                                        </ul>
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                        <h6 class="fw-bold">Cuenta</h6>
                                        <ul class="list-unstyled small">
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/login') ?>">Iniciar sesión</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/register') ?>">Registrarse</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/cart') ?>">Carrito</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/orders') ?>">Mis pedidos</a></li>
                                        </ul>
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                        <h6 class="fw-bold">Ayuda</h6>
                                        <ul class="list-unstyled small">
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/help/shipping') ?>">Envíos</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/help/returns') ?>">Devoluciones</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/help/warranty') ?>">Garantía</a></li>
                                                <li class="mb-2"><a class="link-light text-decoration-none" href="<?= App::url('/contact') ?>">Contacto</a></li>
                                        </ul>

                                        <div class="mt-3">
                                                <div class="small text-white-50 mb-2">Seguinos</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="Instagram">IG</a>
                                                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="Facebook">FB</a>
                                                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="X/Twitter">X</a>
                                                </div>
                                        </div>
                                </div>

                        </div>

                        <hr class="border-white border-opacity-25 my-4">

                        <div class="row g-3 align-items-center">
                                <div class="col-12 col-md-6 small text-white-50 text-center text-md-start">
                                        © <span id="yearNow"></span> Zohan Tech Store. Todos los derechos reservados.
                                </div>

                                <div class="col-12 col-md-6">
                                        <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2 small">
                                                <a class="link-light text-decoration-none text-white-50" href="<?= App::url('/legal/privacy') ?>">Privacidad</a>
                                                <span class="text-white-50">•</span>
                                                <a class="link-light text-decoration-none text-white-50" href="<?= App::url('/legal/terms') ?>">Términos</a>
                                                <span class="text-white-50">•</span>
                                                <a class="link-light text-decoration-none text-white-50" href="<?= App::url('/legal/cookies') ?>">Cookies</a>
                                                <span class="text-white-50">•</span>
                                                <span class="text-white-50">Métodos: PayPal</span>
                                        </div>
                                </div>
                        </div>
                </div>

                <script>
                        (function() {
                                const y = document.getElementById("yearNow");
                                if (y) y.textContent = new Date().getFullYear();
                        })();
                </script>
        </footer>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
                crossorigin="anonymous"></script>

        <!-- Custom JS -->
        <script src="/zohan-ecommerce/public/js/script.js"></script>
        <script src="/zohan-ecommerce/public/js/invoiceDetail.js"></script>


</body>
        
</html>
