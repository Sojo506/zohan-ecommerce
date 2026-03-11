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

        // soportar htaccess o ?url=
        $uri = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($uri === '') {
            $uri = '/';
        }

        // separar query params si vienen dentro de url=/ruta?x=1
        if (strpos($uri, '?') !== false) {
            [$uriPath, $uriQuery] = explode('?', $uri, 2);
            $uri = $uriPath;

            $params = [];
            parse_str($uriQuery, $params);
            $_GET = array_merge($_GET, $params);
        }

        // normalizar path
        $uri = parse_url($uri, PHP_URL_PATH);

        if ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        $routeFound = false;
        $params = [];

        foreach ($this->routes[$method] ?? [] as $route => $controller) {

            // convertir {id} en regex
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_-]+)', $route);

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {

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

        call_user_func_array([$controllerInstance, $methodName], $params);
    }
}