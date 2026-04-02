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
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 col-xl-5">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <div class="p-4 p-md-5 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h1 class="h3 fw-bold mb-1">Restablecer contraseña</h1>
                                <p class="text-muted mb-0">Ingresa tu nueva contraseña para recuperar el acceso.</p>
                            </div>
                            <a href="<?= App::url('/login') ?>" class="btn btn-outline-dark">
                                Volver al login
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="<?= App::url('/reset-password') ?>" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Nueva contraseña</h2>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password"
                                            name="password"
                                            class="form-control"
                                            id="pass1"
                                            placeholder="Nueva contraseña"
                                            minlength="6"
                                            required>
                                        <label for="pass1">Nueva contraseña</label>
                                        <div class="invalid-feedback">
                                            La contraseña debe tener mínimo 6 caracteres.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password"
                                            name="password2"
                                            class="form-control"
                                            id="pass2"
                                            placeholder="Confirmar contraseña"
                                            minlength="6"
                                            required>
                                        <label for="pass2">Confirmar contraseña</label>
                                        <div class="invalid-feedback">
                                            Debes confirmar tu contraseña.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showPass">
                                        <label class="form-check-label" for="showPass">
                                            Mostrar contraseñas
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-grid gap-2">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    Guardar nueva contraseña
                                </button>
                                <a class="btn btn-outline-secondary btn-lg" href="<?= App::url('/login') ?>">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('showPass');
        const pass1 = document.getElementById('pass1');
        const pass2 = document.getElementById('pass2');

        if (!toggle || !pass1 || !pass2) {
            return;
        }

        toggle.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            pass1.type = type;
            pass2.type = type;
        });
    });
</script>
