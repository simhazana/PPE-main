<?php
namespace Core;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, callable|array $handler): void  { $this->routes['GET'][$path]  = $handler; }
    public function post(string $path, callable|array $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $path): void {
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) { http_response_code(404); echo '404'; return; }
        if (is_array($handler)) { [$class, $m] = $handler; (new $class)->{$m}(); return; }
        $handler();
    }
}
