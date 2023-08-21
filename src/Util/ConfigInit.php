<?php

namespace App\Util;

use App\Router\Route;
use Symfony\Component\Yaml\Yaml;
use Exception;
use App;

class ConfigInit
{
    /**
     * @return void
     * @throws Exception
     */
    public function provideRoutes(): void
    {
        $routes = Yaml::parseFile('../config/routes.yaml');
        $routesCollection = [];
        $routesAliases = [];

        foreach($routes['Routes'] as $name => $route) {
            if (is_array($route['callable'])) {
                $callable = $route['callable'][0];
                $type = 'class';
            } else {
                $callable = $route['callable'];
                $type = 'method';
            }

            if (class_exists($callable)) {
                $routesCollection[$name] = new Route($route['path'], $route['callable'], $type);
                if(isset($route['alternative'])) {
                    if (is_array($route['alternative'])) {
                        foreach ($route['alternative'] as $alias) {
                            $routesAliases[$alias] = $name;
                        }
                    } else {
                        $routesAliases[$route['alternative']] = $name;
                    }
                }
            } else {
                throw new Exception('Class not found');
            }
        }

        App::$container->addParameter('RoutesCollection', $routesCollection);
        App::$container->addParameter('RoutesAliases', $routesAliases);
    }
}