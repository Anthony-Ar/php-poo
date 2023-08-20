<?php

namespace App\Router;

use App\Controller\ClientsController;
use App\Controller\TransactionsController;
use Symfony\Component\HttpFoundation\Request;

class RouteList
{
    public static array $routes =
        [
            'transactions' => ['/', [TransactionsController::class, 'index']],
            'clients' => ['/clients', [ClientsController::class, 'index']],
            'clients_unique' => ['/clients/{id}', [ClientsController::class, 'uniqueView']]
        ];

    public function getMethodDependenciesAllowed(): array
    {
        return [
            'request' => Request::createFromGlobals()
        ];
    }

    public function resolveMethodDependencies($args): array
    {
        foreach ($this->getMethodDependenciesAllowed() as $name => $dependency) {
            if (in_array($name, $args)) {
                $args[$name] = $dependency;
            }
        }

        return $args;
    }
}