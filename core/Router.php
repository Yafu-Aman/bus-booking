<?php

class Router {

    private $routes = [
        'GET'  => [],
        'POST' => []
    ];

    public function get($path, $controller, $method) {
        $this->routes['GET'][$path] = [$controller, $method];
    }

    public function post($path, $controller, $method) {
        $this->routes['POST'][$path] = [$controller, $method];
    }

    public function dispatch() {
        $page   = $_GET['page'] ?? 'home';
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$page])) {
            [$controllerClass, $action] = $this->routes[$method][$page];

            require_once __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

            $controller = new $controllerClass();
            $controller->$action();

        } else {
            http_response_code(404);
            echo '<h2>404 - Page not found</h2>';
        }
    }
}