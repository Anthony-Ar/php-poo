<?php
require '../vendor/autoload.php';

use App\Router\Router;
use App\Router\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\DependencyInjection\Container;
use TBoileau\DependencyInjection\ContainerInterface;

class App
{
    private Router $router;
    public static ContainerInterface $container;
    public function __construct()
    {
        App::$container = new Container();
        App::$container->addParameter('DB_HOST', 'localhost');
        $this->router = new Router((new RequestContext())->fromRequest(Request::createFromGlobals()));
        $this->router->match();
    }
}

new App;