<?php

namespace App\Router;

class RouteCollection
{
    private array $routes;
    private array $routesAliases;

    public function __construct()
    {
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        foreach (RouteList::$routes as $name => $route) {
            $this->register($name, $route[0], $route[1]);
        }
    }

    private function register(string $name, array|string $path, $callable): void
    {
        $type = is_array($callable) ? 'class' : 'method';

        if (is_array($path)) {
            $this->routes[$name] = new Route($path[0], $callable, $type);
            array_shift($path);
            foreach ($path as $alias) {
                $this->routesAliases[$alias] = $name;
            }

        } else {
            $this->routes[$name] = new Route($path, $callable, $type);
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function get(string $name): Route
    {
        return $this->routes[$name];
    }

    public function getRoutesAliases(): array
    {
        return $this->routesAliases;
    }
}