<?php
namespace App\Core;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch($uri)
    {
    foreach ($this->routes as $route) {
        if ($route['path'] === $uri) {
            list($controllerName, $methodName) = explode('@', $route['handler']);

            $controllerClass = "App\\Controllers\\$controllerName";
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                    return; 
                }
            }
        }
    }

    }
}