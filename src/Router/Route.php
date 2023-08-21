<?php

namespace App\Router;

use App;
use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;

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

    private function methodDependenciesAllowed(): array
    {
        return [
            'request' => Request::createFromGlobals()
        ];
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

    public function resolveArguments(): array
    {
        if($this->callableType === 'class') {
            $params = (new ReflectionClass($this->callable[0]))->getMethod($this->callable[1])->getParameters();
        } else {
            $params = (new ReflectionFunction($this->callable))->getParameters();
        }
        return array_map(fn (ReflectionParameter $param) => $param->getName(), $params);
    }

    public function resolveMethodDependencies($args): array
    {
        foreach ($this->methodDependenciesAllowed() as $name => $dependency) {
            if (in_array($name, $args)) {
                $args[$name] = $dependency;
            }
        }

        return $args;
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