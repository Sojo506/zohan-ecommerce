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
                        <a class="nav-link cart-link" href="<?= App::url('/cart') ?>" aria-label="Carrito">
                            <span class="cart-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zM7.2 6l.94 4.5h8.28l1.26-5.5H6.21L5.27 2H2v2h2l2.1 9.9c.1.5.55.85 1.06.85h10.4c.5 0 .94-.35 1.05-.83L21 6H7.2z"/>
                                </svg>
                            </span>
                            <?php if (!empty($cartCount)): ?>
                                <span class="cart-badge"><?= (int)$cartCount ?></span>
                            <?php endif; ?>
                        </a>
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

