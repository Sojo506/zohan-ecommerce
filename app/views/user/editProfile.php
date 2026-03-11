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
            <div class="col-12 col-lg-8 col-xl-7">

                <div class="card border-0 shadow rounded-4 overflow-hidden">

                    <!-- Header -->
                    <div class="p-4 p-md-5 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                            <div>
                                <h1 class="h3 fw-bold mb-1">Editar perfil</h1>
                                <p class="text-muted mb-0">Actualiza tu información personal.</p>
                            </div>

                            <a href="<?= App::url('/profile') ?>" class="btn btn-outline-dark">
                                Volver al perfil
                            </a>

                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="<?= App::url('/update') ?>" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Datos personales</h2>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text"
                                            name="nombre"
                                            class="form-control"
                                            id="nombre"
                                            value="<?= htmlspecialchars($user['NOMBRE']) ?>"
                                            required>
                                        <label for="nombre">Nombre</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text"
                                            name="apellido_paterno"
                                            class="form-control"
                                            id="apellido_paterno"
                                            value="<?= htmlspecialchars($user['APELLIDO_PATERNO']) ?>"
                                            required>
                                        <label for="apellido_paterno">Apellido paterno</label>
                                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text"
                                            name="apellido_materno"
                                            class="form-control"
                                            id="apellido_materno"
                                            value="<?= htmlspecialchars($user['APELLIDO_MATERNO'] ?? '') ?>">
                                        <label for="apellido_materno">Apellido materno</label>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-5 mb-4">
                                <h2 class="h6 fw-bold text-uppercase text-muted mb-0">Contacto</h2>
                                <hr class="mt-3">
                            </div>

                            <div class="row g-3">

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email"
                                            name="correo"
                                            class="form-control"
                                            id="correo"
                                            value="<?= htmlspecialchars($user['CORREO'] ?? '') ?>"
                                            required>
                                        <label for="correo">Correo</label>
                                        <div class="invalid-feedback">Ingresa un correo válido.</div>
                                    </div>
                                </div>

                            </div>

                            <!-- Actions -->
                            <div class="mt-4 d-grid gap-2">

                                <button class="btn btn-primary btn-lg" type="submit">
                                    Guardar cambios
                                </button>

                                <a class="btn btn-outline-secondary btn-lg" href="<?= App::url('/profile') ?>">
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