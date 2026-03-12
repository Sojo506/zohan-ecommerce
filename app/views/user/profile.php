<?php if (!empty($_SESSION['flash_success'])): ?>
<div class="alert alert-success">
    <?= $_SESSION['flash_success']; ?>
</div>
<?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
<div class="alert alert-danger">
    <?= $_SESSION['flash_error']; ?>
</div>
<?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
<main>
    <section class="bg-light border-bottom">
        <div class="container py-5">

            <!-- Título -->
            <div class="row">
                <div class="col-12">
                    <h2 class="text-start">Perfil de Usuario</h2>
                </div>
            </div>

            <!-- Card perfil -->
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-lg-8">

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-semibold mb-1">
                                Hola,
                                <span class="text-primary">
                                    <?= $user['NOMBRE'] . " " . $user['APELLIDO_PATERNO'] ?>
                                </span>
                            </h3>
                            <p class="text-muted mb-3">
                                <?= $user['CORREO'] ?>
                            </p>
                            <hr>
                            <div class="row text-center mb-4">
                                <div class="col">
                                    <h5 class="fw-bold mb-0">
                                        <?= $stats['total_compras'] ?? 0 ?>
                                    </h5>
                                    <small class="text-muted">Compras</small>
                                </div>

                                <div class="col">
                                    <h5 class="fw-bold mb-0">
                                        <?= date("M Y", strtotime($user['FECHA_REGISTRO'])) ?>
                                    </h5>
                                    <small class="text-muted">Miembro desde</small>
                                </div>

                                <div class="col">
                                    <h5 class="fw-bold mb-0">
                                        $<?= number_format($stats['dinero_gastado'] ?? 0, 0, ',', '.') ?>
                                    </h5>
                                    <small class="text-muted">Total gastado</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?= App::url('/editProfile') ?>" class="btn btn-primary">
                                    Editar perfil
                                </a>
                                <a href="/user/change-password" class="btn btn-outline-secondary">
                                    Cambiar contraseña
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="mt-5">
                <h4 class="mb-4">Mis compras</h4>
                <div class="row g-3">

                    <?php if (!empty($facturas)): ?>

                        <?php foreach ($facturas as $factura): ?>

                            <div class="col-12 col-sm-6 col-lg-3">

                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-body">

                                        <h6 class="fw-bold mb-1">
                                            Factura #<?= $factura['ID_FACTURA'] ?>
                                        </h6>

                                        <p class="text-muted small mb-1">
                                            <?= $factura['TOTAL_PRODUCTOS'] ?> productos
                                        </p>

                                        <p class="text-muted small mb-3">
                                            Realizada el <?= date("d M Y", strtotime($factura['FECHA_FACTURA'])) ?>
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>
                                                <p class="text-muted small mb-0">Total de la factura</p>
                                                <span class="fw-bold">
                                                    $ <?= number_format($factura['TOTAL'], 0, ',', '.') ?>
                                                </span>
                                            </div>

                                            <a href="/factura/<?= $factura['ID_FACTURA'] ?>"
                                                class="btn btn-sm btn-outline-dark">
                                                Ver detalle
                                            </a>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="col-12">
                            <div class="alert alert-light border text-center">
                                Aún no has realizado compras.
                            </div>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </section>
</main>