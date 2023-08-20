<?php

namespace App\Router;

use App\Controller\ClientsController;
use App\Controller\TransactionsController;


class RouteList
{
    public static array $routes =
        [
            'transactions' => ['/', [TransactionsController::class, 'index']],
            'clients' => ['/clients', [ClientsController::class, 'index']],
            'clients_unique' => ['/clients/{id}', [ClientsController::class, 'uniqueView']]
        ];
}