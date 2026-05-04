<?php 
namespace Core;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, callable|array $handler): void  { $this->routes['GET'][$path]  = $handler; }
    public function post(string $path, callable|array $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $path): void {
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $pattern => $handler) {
            // Route exacte
            if ($pattern === $path) {
               $this->call($handler, []);
                return;
            }
            // Route regex
            if ($pattern[0] === '#' && preg_match($pattern, $path, $matches)) {
                array_shift($matches); // enlève le match complet
                $this->call($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Page introuvable';
    }

    private function call(callable|array $handler, array $params): void {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            (new $class)->{$method}(...$params);
            return;
        }
        $handler(...$params);
    }
}

/*
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
*/