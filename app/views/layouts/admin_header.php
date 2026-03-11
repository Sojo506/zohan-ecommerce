<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> | Zohan Tech Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="/zohan-ecommerce/public/css/style.css">

</head>

<body class="admin-body">

    <div class="admin-wrapper">

        <aside class="admin-sidebar" id="adminSidebar">

            <!-- USER INFO ARRIBA -->

            <div class="admin-user-box">

                <div class="admin-user-name">
                    <?= htmlspecialchars($_SESSION['user']['nombre'] ?? '') ?>
                </div>

                <div class="admin-user-role">
                    <?= htmlspecialchars($_SESSION['user']['tipo'] ?? '') ?>
                </div>

            </div>

            <!-- NAV -->

            <nav class="admin-sidebar-nav">

                <a href="<?= App::url('/admin') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'dashboard' ? 'active' : '' ?>">
                    Dashboard
                </a>

                <a href="<?= App::url('/admin/products') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'products' ? 'active' : '' ?>">
                    Productos
                </a>

                <a href="<?= App::url('/admin/categories') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'categories' ? 'active' : '' ?>">
                    Categorías
                </a>

                <a href="<?= App::url('/admin/brands') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'brands' ? 'active' : '' ?>">
                    Marcas
                </a>

                <a href="<?= App::url('/admin/inventory') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'inventory' ? 'active' : '' ?>">
                    Inventario
                </a>

                <a href="<?= App::url('/admin/comments') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'comments' ? 'active' : '' ?>">
                    Comentarios
                </a>

                <a href="<?= App::url('/admin/users') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'users' ? 'active' : '' ?>">
                    Usuarios
                </a>

                <a href="<?= App::url('/admin/sales') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'sales' ? 'active' : '' ?>">
                    Ventas
                </a>

                <a href="<?= App::url('/admin/invoices') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'invoices' ? 'active' : '' ?>">
                    Facturas
                </a>

                <a href="<?= App::url('/admin/coupons') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'coupons' ? 'active' : '' ?>">
                    Cupones
                </a>

                <a href="<?= App::url('/admin/promotions') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'promotions' ? 'active' : '' ?>">
                    Promociones
                </a>

                <a href="<?= App::url('/admin/audit') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'audit' ? 'active' : '' ?>">
                    Auditoría
                </a>

            </nav>

            <!-- FOOTER -->

            <div class="admin-sidebar-footer">

                <a href="<?= App::url('/') ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                    Ir al sitio
                </a>

                <a href="<?= App::url('/logout') ?>" class="btn btn-danger btn-sm w-100">
                    Cerrar sesión
                </a>

            </div>

        </aside>

        <main class="admin-main">

            <header class="admin-topbar">

                <div>

                    <button class="admin-menu-toggle d-md-none"
                        id="sidebarToggle"
                        type="button">
                        ☰
                    </button>

                    <h1 class="admin-page-title mb-0">
                        <?= htmlspecialchars($pageTitle ?? 'Panel') ?>
                    </h1>

                </div>

            </header>

            <section class="admin-content">