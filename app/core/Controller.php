<?php

// Clase base de controladores: centraliza cómo se renderizan vistas públicas y del panel administrativo.
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        // Expone cada clave del arreglo como variable utilizable dentro de la vista.
        extract($data);

        // Las vistas públicas comparten el layout principal del sitio.
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    protected function adminView(string $view, array $data = []): void
    {
        // El panel usa un layout separado para mantener su propia navegación y estilos.
        extract($data);

        require_once __DIR__ . '/../views/layouts/admin_header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layouts/admin_footer.php';
    }
}
