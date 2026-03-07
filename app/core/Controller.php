<?php

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    protected function adminView(string $view, array $data = []): void
    {
        extract($data);

        require_once __DIR__ . '/../views/layouts/admin_header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layouts/admin_footer.php';
    }
}