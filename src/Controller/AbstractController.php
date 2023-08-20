<?php
namespace App\Controller;

use App;
use App\Util\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * Rendu d'un template
     */
    public function render(string $path, array $params = [], Response $response = null): Response
    {
        if($response === null) {
            $response = new Response();
        }

        $this->getContainer(View::class)->render($path, $params);
        return $response;
    }

    /**
     * Redirection
     */
    public function redirect(string $path): RedirectResponse
    {
        $response = new RedirectResponse($path);
        return $response->send();
    }

    /**
     * Appel d'une dépendance
     */
    public function getContainer($id): object
    {
        return App::$container->get($id);
    }

    /**
     * Appel d'un paramètre
     */
    public function getParameter($id): object
    {
        return App::$container->getParameter($id);
    }
}