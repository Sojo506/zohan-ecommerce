<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Zohan Tech Store</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <!-- Navbar simple -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Zohan Tech Store</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
            // Aquí se carga la vista dinámica
            if (isset($view)) {
                require_once __DIR__ . '/../' . $view . '.php';
            }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/js/script.js"></script>

</body>
</html>