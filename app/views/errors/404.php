<div class="not-found-page py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="not-found-card text-center">
                <span class="not-found-code">404</span>
                <h1 class="not-found-title mt-3 mb-3">No encontramos esa página</h1>
                <p class="not-found-text mb-3">
                    La ruta que intentaste abrir no existe o ya no está disponible.
                </p>

                <?php if (!empty($requestedPath)): ?>
                    <p class="not-found-path mb-4">
                        Ruta solicitada:
                        <code><?= htmlspecialchars($requestedPath, ENT_QUOTES, 'UTF-8') ?></code>
                    </p>
                <?php endif; ?>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="<?= App::url('/') ?>" class="btn btn-dark px-4">
                        Volver al inicio
                    </a>
                    <a href="<?= App::url('/shop') ?>" class="btn btn-outline-dark px-4">
                        Ir al catalogo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
