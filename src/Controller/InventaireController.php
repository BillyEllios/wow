<?php

namespace App\Controller;

use App\Repository\PersonnageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireController extends AbstractController
{   
    public function __construct(private EntityManagerInterface $em, 
    private PersonnageRepository $personnageRepository)
    {
        
    }

    #[Route('/inventaire/{personnage_id}', name: 'app_inventaire', methods : ['GET'])]
    public function index($personnage_id): Response
    {
        $personnage = $this->personnageRepository->findOneBy(['id' => $personnage_id]);

        return $this->json(['Nombre d\'arme dans l\'inventaire :' => count($personnage->getArmes())]);
    }
}
