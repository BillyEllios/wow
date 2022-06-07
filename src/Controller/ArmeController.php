<?php

namespace App\Controller;

use App\Repository\ArmeRepository;
use App\Repository\PersonnageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArmeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em,private PersonnageRepository $personnageRepository,private ArmeRepository $armeRepository) {
    }

    #[Route('/setArme/{personnage_id}/{arme_id}', name: 'app_personnage_index', methods: ['GET'])]
    public function index($personnage_id, $arme_id): Response
    {
        $errors = [];

        $personnage = $this->personnageRepository->findOneBy(['id' => $personnage_id]);
        if (!$personnage) {
            $errors[] = 'Personnage not found';
        }
        $arme = $this->armeRepository->findOneBy(['id' => $arme_id]);
        if (!$arme) {
            $errors[] = 'Arme not found';
        }
        
        if ($arme && $personnage) {
            $personnage->addArme($arme);
    
            $this->em->persist($personnage);
            $this->em->persist($arme);
            $this->em->flush();
        }

        return $this->json(['ok' => count($errors) == 0, 'errors' => $errors]);
    }
}