<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Zohan Tech Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link rel="stylesheet" href="/zohan-ecommerce/public/css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand fw-bold" href="<?= App::url('/') ?>">
                Zohan Tech Store
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= App::url('/tienda') ?>">Tienda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= App::url('/cart') ?>">Carrito</a>
                    </li>

                    <?php if (isset($_SESSION['user'])): ?>

                        <li class="nav-item">
                            <span class="nav-link">
                                <a class="nav-link text-info" href="<?= App::url('/profile') ?>">
                                    Hola <?= ($_SESSION['user']['nombre']) ?>
                                </a>
                            </span>
                        </li>

                        <?php if ($_SESSION['user']['tipo'] === 'ADMIN'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-semibold" href="<?= App::url('/admin') ?>">
                                    ADMIN
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= App::url('/logout') ?>">
                                Salir
                            </a>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= App::url('/login') ?>">
                                Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= App::url('/register') ?>">
                                Registro
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>

        </div>
    </nav>

    <div class="container mt-4">

