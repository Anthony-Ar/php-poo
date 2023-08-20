<?php

namespace App\Util;

use App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private const FILEPATH = '../templates/';
    private ?Environment $environment = null;

    /**
     * Build une nouvelle instance si besoin et renvoi l'instance en cours
     */
    public function getEnvironment(): Environment
    {
        if (is_null($this->environment)) {
            $this->environment = $this->build();
        }
        return $this->environment;
    }

    /**
     * Créer l'environnement Twig et injecte les filtres personnalisés
     */
    public function build(): Environment
    {
        $loader = new FilesystemLoader(View::FILEPATH);
        $environment = new Environment($loader, [
            'auto_reload' => true
        ]);
        
        $twigFilters = (new TwigFilterExtension())->getFilters();
        
        if (count($twigFilters) > 0) {
            foreach ($twigFilters as $filter) {
                $environment->addFilter($filter);
            }
        }
        
        return $environment;
    }

    /**
     * Rendu d'un template
     */
    public function render(string $path, array $params = [])
    {
        return $this->getEnvironment()->display($path, $params);
    }
}