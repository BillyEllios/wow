<?php

namespace App\Services;

class PersonnageService {
    private const N_PERSONNAGE = ['Tartopoil','Tartinopoil','Jeantartuf','Megafort','Loktar','Jeanguy'];

    public function getPersonnage(): string {
        return PersonnageService::N_PERSONNAGE[array_rand(PersonnageService::N_PERSONNAGE)];
    }
  
}