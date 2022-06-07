<?php

namespace App\Controller;

use App\Repository\ClasseRepository;
use App\Repository\PersonnageRepository;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonnageController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private ClasseRepository $classeRepository, private RaceRepository $raceRepository,private PersonnageRepository $personnageRepository)
    {

    }

    #[Route('/countRace/{race_id}', name: 'app_personnage_race', methods: ['GET'])]
    public function countRace($race_id): Response
    {
        $race = $this->raceRepository->findOneBy(['id' => $race_id]);
        $persos = $this->personnageRepository->findBy(['race' => $race]);
        
        return $this->json(['count' => count($persos)]);
    }

    #[Route('/countClasse/{classe_id}', name: 'app_personnage_classe', methods: ['GET'])]
    public function countClasse($classe_id): Response
    {
        $classe = $this->classeRepository->findOneBy(['id' => $classe_id]);
        $persos = $this->personnageRepository->findBy(['classe' => $classe]);

        return $this->json(['count' => count($persos)]);
    }

    #[Route('/countFaction/{faction_id}', name: 'app_personnage_faction', methods: ['GET'])]
    public function countFaction($faction_id): Response
    {
        $faction = $this->raceRepository->find(['id' => $faction_id]);
        $persos = $this->personnageRepository->findBy(['faction' => $faction]);

        return $this->json(['count' => count($persos)]);
    }
}
