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
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Recuperar contraseña</h3>
                            <p class="text-muted">
                                Ingresa tu usuario o correo y te enviaremos un código para continuar.
                            </p>
                        </div>

                        <form method="POST" action="<?= App::url('/forgot-password') ?>">
                            <div class="mb-3">
                                <label class="form-label">
                                    Usuario o correo
                                </label>

                                <input
                                    type="text"
                                    name="user"
                                    class="form-control form-control-lg"
                                    placeholder="usuario o correo"
                                    required>
                            </div>

                            <div class="d-grid mb-3">
                                <button class="btn btn-dark btn-lg">
                                    Enviar código
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="text-center">
                            <a href="<?= App::url('/login') ?>" class="fw-semibold text-decoration-none">
                                Volver a iniciar sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
