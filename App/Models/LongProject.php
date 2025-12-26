<?php

require_once __DIR__ . '/Project.php';

class LongProject extends Project
{
    public function calculateDuration(): string
    {
        return self::calculateDurationStatic($this->dateDebut, $this->dateFin);
    }
    
    public static function calculateDurationStatic(string $dateStart, string $dateEnd): string
    {
        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);
        $interval = $start->diff($end);
        
        $months = ($interval->y * 12) + $interval->m;
        
        return $months . " mois";
    }
}
