<?php

namespace App\Router;

use App;

class RouteCollection
{
    private array $routes = [];
    private array $routesAliases = [];

    public function __construct() {
        $this->routes = App::$container->getParameter('RouteCollection');
        $this->routesAliases = App::$container->getParameter('RoutesAliases');
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