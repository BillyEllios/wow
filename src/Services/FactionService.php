<?php

namespace App\Services;

use App\Repository\FactionRepository;

class FactionService {
    public function __construct(private FactionRepository $factionRepository) {

    }

    public function getFromName($name) {
        return $this->factionRepository->findOneBy(['name' => $name]);
    }
}