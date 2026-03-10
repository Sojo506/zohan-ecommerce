<?php

class Router
{
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
        $method = $_SERVER['REQUEST_METHOD'];

        // sin htaccess: usamos ?url=/ruta
        $uri = $_GET['url'] ?? '/';
        if ($uri === '') {
            $uri = '/';
        }

        // Si vienen query params embebidos en url=/ruta?x=1, separarlos.
        if (strpos($uri, '?') !== false) {
            [$uriPath, $uriQuery] = explode('?', $uri, 2);
            $uri = $uriPath;

            $params = [];
            parse_str($uriQuery, $params);
            $_GET = array_merge($_GET, $params);
        }

        // normalizar (por si viene sin slash)
        if ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 - Pagina no encontrada (ruta: {$uri})";
            return;
        }

        [$controllerName, $methodName] = explode('@', $this->routes[$method][$uri]);

        $path = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!file_exists($path)) {
            http_response_code(500);
            echo "Controlador no existe: {$controllerName}";
            return;
        }

        require_once $path;

        $controllerInstance = new $controllerName();
        if (!method_exists($controllerInstance, $methodName)) {
            http_response_code(500);
            echo "Metodo no existe: {$controllerName}@{$methodName}";
            return;
        }

        $controllerInstance->$methodName();
    }
}
