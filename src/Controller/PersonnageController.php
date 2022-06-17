<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Repository\ClasseRepository;
use App\Repository\FactionRepository;
use App\Repository\PersonnageRepository;
use App\Repository\RaceRepository;
use App\Repository\ArmeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonnageController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em,
    private ArmeRepository $armeRepository, 
    private FactionRepository $factionRepository, 
    private ClasseRepository $classeRepository, 
    private RaceRepository $raceRepository,
    private PersonnageRepository $personnageRepository)
    {

    }

    #[Route('/personnage', name: 'ap_personnage_index')]
    public function index(): Response
    {
        $user = 'thierry';
        return $this->render('personnage/index.html.twig', [
            'controller_name' => $user,
        ]);
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
        $faction = $this->factionRepository->find($faction_id);
        $races = $this->raceRepository->findBy(['faction' => $faction]);

        $persos = [];
        foreach( $races as $race) {
            $persos = array_merge($persos, $this->personnageRepository->findBy(['race' => $race]));
        }

        $infos = [];
        foreach( $persos as $perso) {
            $info = [];
            $info['pseudo'] = $perso->getPseudo();
            $info['race'] = $perso->getRace()->getName();
            $info['faction'] = $perso->getRace()->getFaction()->getName();
            $infos[] = $info;
        }

        return $this->json($infos);
    }

    #[Route('/tradeArme/{perso1_id}/{perso2_id}/{arme_id}/', name:'app_personnage_trade', methods: ['GET'])]
    public function tradeArme($perso1_id, $perso2_id, $arme_id): Response
    {
        $perso1 = $this->personnageRepository->findOneBy(['id' => $perso1_id]);
        $perso2 = $this->personnageRepository->findOneBy(['id' => $perso2_id]);
        $arme = $this->armeRepository->findOneBy(['id' => $arme_id]);

        $perso1->removeArme($arme);
        $perso2->addArme($arme);
        
        return $this->json($arme->getPersonnage()->getPseudo());
    }
}
