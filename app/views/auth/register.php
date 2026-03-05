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

<section class="py-5 ">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">

                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="p-4 p-md-5 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h1 class="h3 fw-bold mb-1">Crear cuenta</h1>
                                <p class="text-muted mb-0">Te enviaremos un código al correo para activar tu cuenta.</p>
                            </div>
                            <a href="<?= App::url('/login') ?>" class="btn btn-outline-dark">
                                Ya tengo cuenta
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">

                        <!-- Alertas opcionales -->
                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger rounded-3 mb-4">
                                <?= $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['success'])): ?>
                            <div class="alert alert-success rounded-3 mb-4">
                                <?= $_SESSION['success'];
                                unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= App::url('/register') ?>" class="needs-validation" novalidate>

                            <!-- Datos de cuenta -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Datos de cuenta</h2>
                                </div>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="identificacion" class="form-control" id="identificacion"
                                            placeholder="Identificación" required>
                                        <label for="identificacion">Identificación</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="username" class="form-control" id="username"
                                            placeholder="Username" required>
                                        <label for="username">Username</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" name="correo" class="form-control" id="correo"
                                            placeholder="Correo" required>
                                        <label for="correo">Correo</label>
                                        <div class="invalid-feedback">Ingresa un correo válido.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Datos personales -->
                            <div class="mt-5 mb-4">
                                <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Datos personales</h2>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text" name="nombre" class="form-control" id="nombre"
                                            placeholder="Nombre" required>
                                        <label for="nombre">Nombre</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno"
                                            placeholder="Apellido paterno" required>
                                        <label for="apellido_paterno">Apellido paterno</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text" name="apellido_materno" class="form-control" id="apellido_materno"
                                            placeholder="Apellido materno">
                                        <label for="apellido_materno">Apellido materno</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Seguridad -->
                            <div class="mt-5 mb-4">
                                <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Seguridad</h2>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" id="pass1"
                                            placeholder="Contraseña" minlength="6" required>
                                        <label for="pass1">Contraseña</label>
                                        <div class="invalid-feedback">Mínimo 6 caracteres.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="password2" class="form-control" id="pass2"
                                            placeholder="Confirmar contraseña" minlength="6" required>
                                        <label for="pass2">Confirmar contraseña</label>
                                        <div class="invalid-feedback">Confirma tu contraseña.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="showPass">
                                            <label class="form-check-label" for="showPass">Mostrar contraseñas</label>
                                        </div>

                                        <div id="passMatch" class="small text-muted"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Acepto los <a href="<?= App::url('/legal/terms') ?>" class="text-decoration-none">Términos</a> y la
                                            <a href="<?= App::url('/legal/privacy') ?>" class="text-decoration-none">Política de privacidad</a>.
                                        </label>
                                        <div class="invalid-feedback">Debes aceptar para continuar.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 d-grid gap-2">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    Registrarme
                                </button>
                                <a class="btn btn-outline-secondary btn-lg" href="<?= App::url('/login') ?>">
                                    Volver a iniciar sesión
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
    (function() {
        const pass1 = document.getElementById("pass1");
        const pass2 = document.getElementById("pass2");
        const showPass = document.getElementById("showPass");
        const passMatch = document.getElementById("passMatch");

        function updateMatch() {
            if (!pass1 || !pass2 || !passMatch) return;
            if (!pass2.value) {
                passMatch.textContent = "";
                passMatch.className = "small text-muted";
                return;
            }

            if (pass1.value === pass2.value) {
                passMatch.textContent = "Las contraseñas coinciden";
                passMatch.className = "small text-success";
            } else {
                passMatch.textContent = "Las contraseñas no coinciden";
                passMatch.className = "small text-danger";
            }
        }

        pass1 && pass1.addEventListener("input", updateMatch);
        pass2 && pass2.addEventListener("input", updateMatch);

        showPass && showPass.addEventListener("change", () => {
            const t = showPass.checked ? "text" : "password";
            pass1 && pass1.setAttribute("type", t);
            pass2 && pass2.setAttribute("type", t);
        });

        // Bootstrap validation UI
        const forms = document.querySelectorAll(".needs-validation");
        Array.from(forms).forEach((form) => {
            form.addEventListener("submit", (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            }, false);
        });
    })();
</script>