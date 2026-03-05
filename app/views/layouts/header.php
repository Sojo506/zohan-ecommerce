<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
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

            <a class="navbar-brand" href="<?= App::url('/') ?>">Zohan Tech Store</a>

            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>

                    <li class="nav-item">
                        <span class="nav-link">
                            Hola <?= $_SESSION['user']['nombre'] ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= App::url('/logout') ?>">Salir</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= App::url('/login') ?>">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= App::url('/register') ?>">Registro</a>
                    </li>

                <?php endif; ?>
            </ul>

        </div>
    </nav>

    <div class="container mt-4">