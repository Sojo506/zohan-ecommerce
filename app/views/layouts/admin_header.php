<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= ($pageTitle ?? 'Admin') ?> | Zohan Tech Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/zohan-ecommerce/public/css/style.css">

</head>

<body class="admin-body">

    <div class="admin-wrapper">

        <!-- SIDEBAR -->

        <aside class="admin-sidebar" id="adminSidebar">

            <!-- USER BOX -->

            <div class="admin-user-box">

                <div class="admin-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>

                <div class="admin-user-info">

                    <div class="admin-user-name">
                        <?= ($_SESSION['user']['nombre'] ?? '') ?>
                    </div>

                    <div class="admin-user-role">
                        <?= ($_SESSION['user']['tipo'] ?? '') ?>
                    </div>

                </div>

            </div>


            <!-- NAV -->

            <nav class="admin-sidebar-nav">

                <a href="<?= App::url('/admin') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= App::url('/admin/products') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'products' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i>
                    <span>Productos</span>
                </a>

                <a href="<?= App::url('/admin/categories') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'categories' ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i>
                    <span>Categorías</span>
                </a>

                <a href="<?= App::url('/admin/brands') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'brands' ? 'active' : '' ?>">
                    <i class="bi bi-building"></i>
                    <span>Marcas</span>
                </a>

                <a href="<?= App::url('/admin/inventory') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'inventory' ? 'active' : '' ?>">
                    <i class="bi bi-boxes"></i>
                    <span>Inventario</span>
                </a>

                <a href="<?= App::url('/admin/comments') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'comments' ? 'active' : '' ?>">
                    <i class="bi bi-chat-dots"></i>
                    <span>Comentarios</span>
                </a>

                <a href="<?= App::url('/admin/users') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'users' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>

                <a href="<?= App::url('/admin/sales') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'sales' ? 'active' : '' ?>">
                    <i class="bi bi-cart-check"></i>
                    <span>Ventas</span>
                </a>

                <a href="<?= App::url('/admin/invoices') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'invoices' ? 'active' : '' ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Facturas</span>
                </a>

                <a href="<?= App::url('/admin/reports') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'reports' ? 'active' : '' ?>">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Reportes</span>
                </a>

                <a href="<?= App::url('/admin/coupons') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'coupons' ? 'active' : '' ?>">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Cupones</span>
                </a>

                <a href="<?= App::url('/admin/promotions') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'promotions' ? 'active' : '' ?>">
                    <i class="bi bi-megaphone"></i>
                    <span>Promociones</span>
                </a>

                <a href="<?= App::url('/admin/audit') ?>"
                    class="admin-sidebar-link <?= ($currentSection ?? '') === 'audit' ? 'active' : '' ?>">
                    <i class="bi bi-shield-check"></i>
                    <span>Auditoría</span>
                </a>

            </nav>


            <!-- FOOTER -->

            <div class="admin-sidebar-footer">

                <a href="<?= App::url('/') ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                    <i class="bi bi-globe"></i> Ver tienda
                </a>

                <a href="<?= App::url('/logout') ?>" class="btn btn-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>

            </div>

        </aside>


        <!-- MAIN -->

        <main class="admin-main">

            <header class="admin-topbar">

                <div class="d-flex align-items-center gap-3">

                    <button class="admin-menu-toggle d-md-none"
                        id="sidebarToggle"
                        type="button">
                        <i class="bi bi-list"></i>
                    </button>

                    <h1 class="admin-page-title mb-0">
                        <?= ($pageTitle ?? 'Panel') ?>
                    </h1>

                </div>

            </header>

            <section class="admin-content">
