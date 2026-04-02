<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<section class="py-5">
    <div class="container">

        <div class="row justify-content-center align-items-center">

            <div class="col-md-6 col-lg-5">

                <!-- card -->
                <div class="card shadow border-0 rounded-4">

                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold">Iniciar sesi&oacute;n</h3>

                            <p class="text-muted">
                                Accede a tu cuenta de Zohan Tech Store
                            </p>

                        </div>

                        <form method="POST" action="<?= App::url('/login') ?>" autocomplete="off">
                            <!-- Campos fantasma para evitar autocompletado del navegador -->
                            <input type="text" name="fakeusernameremembered" style="display:none" autocomplete="off">
                            <input type="password" name="fakepasswordremembered" style="display:none" autocomplete="new-password">

                            <!-- usuario -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Usuario o correo
                                </label>

                                <input
                                    type="text"
                                    name="user"
                                    class="form-control form-control-lg"
                                    placeholder="usuario o correo"
                                    value=""
                                    autocomplete="off"
                                    autocapitalize="off"
                                    autocorrect="off"
                                    required>

                            </div>

                            <!-- contrase&ntilde;a -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Contrase&ntilde;a
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    placeholder="••••••••"
                                    value=""
                                    autocomplete="new-password"
                                    required>

                            </div>

                            <!-- opciones -->
                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember">

                                    <label class="form-check-label small" for="remember">
                                        Recordarme
                                    </label>

                                </div>

                                <a href="<?= App::url('/forgot-password') ?>" class="small text-decoration-none">
                                    ¿Olvidaste tu contraseña?
                                </a>

                            </div>

                            <!-- boton -->
                            <div class="d-grid mb-3">

                                <button class="btn btn-dark btn-lg">
                                    Iniciar sesi&oacute;n
                                </button>

                            </div>

                        </form>

                        <hr>

                        <!-- registro -->
                        <div class="text-center">

                            <p class="small text-muted mb-0">
                                &iquest;No tienes una cuenta?
                            </p>

                            <a href="<?= App::url('/register') ?>" class="fw-semibold text-decoration-none">
                                Crear cuenta
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
