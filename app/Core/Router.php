<?php

namespace App\Core;

class Router
{
    public static function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');

        $parts = explode('/', $uri);
        $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'DashboardController';
        $method = $parts[1] ?? 'index';
        $param = $parts[2] ?? null;

        $controllerClass = "App\\Controllers\\$controllerName";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                $controller->$method($param);
                return;
            }
        }

        http_response_code(404);
        echo "Página não encontrada.";
    }
}
