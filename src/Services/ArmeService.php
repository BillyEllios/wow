<?php

namespace App\Services;

class ArmeService {
    private const N_ARME = ['Arc','Arbalète','Arme de Jet','Arme de Pugilat','Arme à Feu','Armes d\'Hast','Baguette','Bâton','Epée à une main','Epée à deux mains','Masse à une main','Masse à deux mains'];
    private const T_ARME = ['Corps à corps','A distance'];

    public function getNArme(): string {
        return ArmeService::N_ARME[array_rand(ArmeService::N_ARME)];
    }

    public function getTArme(): string {
        return ArmeService::T_ARME[array_rand(ArmeService::T_ARME)];
    }
  
}