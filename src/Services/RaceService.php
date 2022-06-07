<?php

namespace App\Services;

use App\Entity\Race;
use App\Repository\RaceRepository;

class RaceService {
    public function __construct(private RaceRepository $raceRepository) {}

    public function getRace(): Race {
        $races = $this->raceRepository->findAll();
        return $races[array_rand($races)];
    }

    public function getFromName($name): Race {
        $race = $this->raceRepository->findOneBy(['name' => $name]);
        return $race;
    }
}