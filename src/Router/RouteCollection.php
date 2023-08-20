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

        /** Note : ajouter la possibilité d'avoir des chemins alternatifs
         * Enregistrer $routesAliases[nom de l'alias] = $path
         * Ajouter un match par alias sur la méthode match()
         */

        $this->routes[$name] = new Route($path, $callable, $type);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoutesAliases(): array
    {
        return $this->routesAliases;
    }
}