<?php

// Router HTTP mínimo del proyecto: registra rutas manuales y resuelve "Clase@metodo" en tiempo de ejecución.
class Router
{
    // La tabla se agrupa por método HTTP para que GET y POST puedan compartir la misma URI sin conflicto.
    private array $routes = [];

    public function get(string $uri, string $controller): void
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post(string $uri, string $controller): void
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function dispatch(): void
    {
        // Normaliza la petición actual, busca la primera ruta compatible y ejecuta el controlador asociado.
        $method = $_SERVER['REQUEST_METHOD'];

        // Acepta tanto la ruta amigable guardada en ?url= como la URI nativa del servidor.
        $uri = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        // Permite ejecutar la app desde una subcarpeta sin redefinir cada ruta manualmente.
        if ($basePath !== '/' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        if ($uri === '') {
            $uri = '/';
        }

        $routeFound = false;

        foreach ($this->routes[$method] ?? [] as $route => $controller) {

            // Convierte placeholders como /admin/products/{id} en una regex capturable.
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_-]+)', $route);

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {

                // El router se queda con la primera coincidencia; por eso el orden de registro sí importa.
                $routeFound = true;

                array_shift($matches); // quitar coincidencia completa
                $params = $matches;

                [$controllerName, $methodName] = explode('@', $controller);

                break;
            }
        }

        if (!$routeFound) {
            http_response_code(404);
            echo "404 - Pagina no encontrada (ruta: {$uri})";
            return;
        }

        // El autoload resuelve el archivo del controlador usando su nombre de clase.
        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "Controlador no existe: {$controllerName}";
            return;
        }

        // La ruta define controlador y método como "Clase@metodo", y aquí se resuelven dinámicamente.
        $controllerInstance = new $controllerName();

        if (!method_exists($controllerInstance, $methodName)) {
            http_response_code(500);
            echo "Metodo no existe: {$controllerName}@{$methodName}";
            return;
        }

        // Los valores capturados por la ruta dinámica se inyectan al método en el mismo orden en que aparecen.
        call_user_func_array([$controllerInstance, $methodName], $params);
    }
}
