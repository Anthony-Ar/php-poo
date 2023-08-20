<?php

namespace App\Router;

use App;
use ReflectionClass;
use ReflectionParameter;

class Route
{
    private string $path;
    private $callable;
    private string $callableType;

    public function __construct(string $path, $callable, string $callableType)
    {
        $this->path = $path;
        $this->callable = $callable;
        $this->callableType = $callableType;
    }

    public function resolveDependencies(): ?array
    {
        $dependencies = null;
        $callableClass = new ReflectionClass($this->callable[0]);
        $callableConstructorParams = $callableClass->getConstructor()->getParameters();

        if (!empty($callableConstructorParams)) {
            foreach ($callableConstructorParams as $param) {
                if (!$param->isOptional()) {
                    $dependencies[] = App::$container->get(strval($param->getType()));
                }
            }
        }

        return $dependencies;
    }

    public function resolveArguments(): ?array
    {
        $params = (new ReflectionClass($this->callable[0]))->getMethod($this->callable[1])->getParameters();
        return array_map(fn (ReflectionParameter $param) => $param->getName(), $params);
    }

    public function getRoutePattern(): string
    {
        $pattern = str_replace("/", "\/", $this->path);
        $pattern = sprintf("/^%s$/", $pattern);
        $pattern = preg_replace_callback('/(\{\w+\})/', fn() => sprintf('(%s)?', ".+"), $pattern);
        return $pattern;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function getCallableType(): string
    {
        return $this->callableType;
    }
}