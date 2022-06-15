<?php

namespace App\Services;

use App\Entity\Personnage;
use App\Repository\PersonnageRepository;

class PersonnageService {
    public const N_PERSONNAGE = ['Tartopoil','Tartinopoil','Jeantartuf','Megafort','Loktar','Jeanguy','Ellios', 'Ellyria','Ysirka','Bazaim'];
    public function __construct(private PersonnageRepository $personnageRepository){
        
    }

    public function getPersonnage(): string {
        return PersonnageService::N_PERSONNAGE[array_rand(PersonnageService::N_PERSONNAGE, 3)];
    }
        
    public function getPersonnages(): Personnage {
        $personnages = $this->personnageRepository->findAll();
        return $personnages[array_rand($personnages)];
    }

    public function getPersoByName($pseudo): Personnage {
        return $this->personnageRepository->findOneBy(['pseudo' => $pseudo]);
    }

    public function getFromId($id): Personnage {
        $id = $this->personnageRepository->findOneBy(['id' => $id]);
        return $id;
    }
}