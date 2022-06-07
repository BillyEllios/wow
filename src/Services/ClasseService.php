<?php

namespace App\Services;

use App\Entity\Classe;
use App\Repository\ClasseRepository;

class ClasseService {
    public function __construct(private ClasseRepository $classeRepository) {}

    public function getClasse(): Classe {
        $classes = $this->classeRepository->findAll();
        return $classes[array_rand($classes)];
    }
}