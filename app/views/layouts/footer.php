</div>
<footer class="bg-dark text-white mt-0">
        <div class="container py-5">
                <div class="row g-4">

                        <!-- Brand -->
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

                                <!-- <div class="mt-4">
                                        <div class="small text-white-50 mb-2">Suscribite para recibir promos</div>
                                        <form class="row g-2" action="#" method="post">
                                                <div class="col-8">
                                                        <input type="email" class="form-control" placeholder="correo@ejemplo.com" required>
                                                </div>
                                                <div class="col-4 d-grid">
                                                        <button class="btn btn-primary" type="submit">Unirme</button>
                                                </div>
                                        </form>
                                        <div class="small text-white-50 mt-2">Sin spam. Te podés salir cuando quieras.</div>
                                </div> -->
                        </div>

                        <!-- Links -->
                        <div class="col-6 col-lg-2">
                                <h6 class="fw-bold">Tienda</h6>
                                <ul class="list-unstyled small">
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products">Catálogo</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?sort=top">Top ventas</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?sort=new">Novedades</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?promo=1">Ofertas</a></li>
                                </ul>
                        </div>

                        <div class="col-6 col-lg-2">
                                <h6 class="fw-bold">Categorías</h6>
                                <ul class="list-unstyled small">
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?category=laptops">Laptops</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?category=components">Componentes</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?category=gaming">Gaming</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/products?category=accessories">Accesorios</a></li>
                                </ul>
                        </div>

                        <div class="col-6 col-lg-2">
                                <h6 class="fw-bold">Cuenta</h6>
                                <ul class="list-unstyled small">
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/auth/login">Iniciar sesión</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/auth/register">Registrarse</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/cart">Carrito</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/orders">Mis pedidos</a></li>
                                </ul>
                        </div>

                        <div class="col-6 col-lg-2">
                                <h6 class="fw-bold">Ayuda</h6>
                                <ul class="list-unstyled small">
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/help/shipping">Envíos</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/help/returns">Devoluciones</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/help/warranty">Garantía</a></li>
                                        <li class="mb-2"><a class="link-light text-decoration-none" href="/contact">Contacto</a></li>
                                </ul>

                                <div class="mt-3">
                                        <div class="small text-white-50 mb-2">Seguinos</div>
                                        <div class="d-flex gap-2">
                                                <a class="btn btn-outline-light btn-sm" href="#" aria-label="Instagram">IG</a>
                                                <a class="btn btn-outline-light btn-sm" href="#" aria-label="Facebook">FB</a>
                                                <a class="btn btn-outline-light btn-sm" href="#" aria-label="X/Twitter">X</a>
                                        </div>
                                </div>
                        </div>

                </div>

                <hr class="border-white border-opacity-25 my-4">

                <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-6 small text-white-50">
                                © <span id="yearNow"></span> Zohan Tech Store. Todos los derechos reservados.
                        </div>

                        <div class="col-12 col-md-6">
                                <div class="d-flex flex-wrap justify-content-md-end gap-2 small">
                                        <a class="link-light text-decoration-none text-white-50" href="/legal/privacy">Privacidad</a>
                                        <span class="text-white-50">•</span>
                                        <a class="link-light text-decoration-none text-white-50" href="/legal/terms">Términos</a>
                                        <span class="text-white-50">•</span>
                                        <a class="link-light text-decoration-none text-white-50" href="/legal/cookies">Cookies</a>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="/zohan-ecommerce/public/js/script.js"></script>

</body>

</html>