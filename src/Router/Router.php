<?php

namespace App\Router;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App;

class Router
{
    private RequestContext $request;
    private array $routes;
    private array $routesAliases;

    public function __construct(RequestContext $request)
    {
        $this->request = $request;
        $this->routes = App::$container->getParameter('RoutesCollection');
        $this->routesAliases = App::$container->getParameter('RoutesAliases');
    }

    public function match(): mixed
    {
        foreach ($this->routes as $route) {
            if ($this->pathMatch($route->getPath())) {
                return $this->call($route);
            }
        }

        foreach ($this->routesAliases as $routePath => $routeName) {
            if ($this->pathMatch($routePath)) {
                return $this->call($this->routes[$routeName]);
            }
        }

       return (new RedirectResponse('/'))->send();
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

    private function getRoutePattern(string $path): string
    {
        $pattern = str_replace("/", "\/", $path);
        $pattern = sprintf("/^%s$/", $pattern);
        return preg_replace_callback('/(\{\w+\})/', fn() => sprintf('(%s)?', '.+'), $pattern);
    }

    private function pathMatch(string $path): bool
    {
        return preg_match($this->getRoutePattern($path), $this->getParsedPath());
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
                preg_match($this->getRoutePattern($route->getPath()), $this->getParsedPath(), $matches);
                array_shift($matches);
                foreach ($paramMatches[1] as $i => $parameter) {
                    $values[$parameter] = $matches[$i];
                }
            }

            $values = array_merge($values, $route->resolveMethodDependencies($args));

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