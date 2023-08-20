<?php

namespace App\Helper;

class DateHelper
{
    /** 
     * Calcul la différence entre deux dates et retourne sous forme de nombre (jours)
     */
    public static function getInterval($origin, $target = 'now'): string
    {
        $origin = new \DateTimeImmutable($origin);
        $target = new \DateTimeImmutable($target);
        $interval = $origin->diff($target);
        return $interval->format("%a");
    }
}