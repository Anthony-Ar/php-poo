<?php

namespace App\Util;

use Twig\TwigFilter;
use DateTime;

class TwigFilterExtension
{
    public function getFilters(): array
    {
        return array(
            new TwigFilter('date_formate', array($this, 'date_formate')),
            new TwigFilter('euro_formate', array($this, 'euro_formate'))
        );
    }

    public function date_formate($value): string
    {
        return date_format(new DateTime($value), 'd/m/Y');
    }

    public function euro_formate($value): string
    {
        return number_format(floatval($value), 2, ',', '.').'€';
    }
}