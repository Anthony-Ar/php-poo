<?php

namespace App\Router;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private RequestContext $request;
    private RouteCollection $routes;

    public function __construct(RequestContext $request)
    {
        $this->request = $request;
        $this->routes = new RouteCollection();
    }

    private function call(Route $route): mixed
    {
        $callable = $route->getCallable();
        $argsValue = $this->getArgsValues($route);

        if ($route->getCallableType() === 'class') {
            $callable = [new $callable[0](...$route->resolveDependencies()), $callable[1]];
        } else {
            $callable = [new $callable[0](), $callable[1]];
        }

        return call_user_func_array($callable, $argsValue);
    }

    public function match(): mixed
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($this->pathMatch($route)) {
                return $this->call($route);
            }
        }

        return (new RedirectResponse('/'))->send();
    }

    private function pathMatch(Route $route): bool
    {
        return preg_match($route->getRoutePattern(), $this->getParsedPath());
    }

    private function getParsedPath(): string
    {
        $path = $this->request->getPathInfo();
        while(str_ends_with($path, '/') && strlen($path) > 1) {
            $path = mb_strimwidth($path, 0, -1);
        }
        return $path;
    }

    private function getArgsValues(Route $route): array
    {
        $values = [];
        $args = $route->resolveArguments();

        if(count($args) > 0) {
            preg_match_all("/{(\w+)\}/", $route->getPath(), $paramMatches);

            if (count($paramMatches[1]) > 0) {
                preg_match($route->getRoutePattern(), $this->getParsedPath(), $matches);
                array_shift($matches);
                foreach ($paramMatches[1] as $i => $parameter) {
                    $values[$parameter] = $matches[$i];
                }
            }

            if (in_array('request', $args)) {
                /**
                 * Note : ajouter la possibilité d'automatiser les injections et
                 * définir une liste de dépendances autorisées qui ne font pas partie des paramètres d'une route
                 **/
                $values['request'] = Request::createFromGlobals();
            }

            return array_map(
                function (string $name) use ($values) {
                    return $values[$name];
                },
                $args
            );
        }

        return [];
    }
}