<?php

namespace App\Controller\Admin;

use App\Entity\Arme;
use App\Entity\Classe;
use App\Entity\Faction;
use App\Entity\Personnage;
use App\Entity\Race;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
        
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(RaceCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('WoW');
    }

    //Change le CSS de la manière dont on l'a définit 
    // Dans ./assests/styles/'something.css
    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('');
    }

    //Donne toute les actions de AbstractCrudController
    // A tout mes CRUD Controller
    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Entities', 'fa fa-hat-wizard');

        yield MenuItem::linkToCrud('User', 'fa fa-title-text', User::class);
        yield MenuItem::linkToCrud('Faction', 'fa fa-title-text', Faction::class);
        yield MenuItem::linkToCrud('Classe', 'fa fa-cauldron', Classe::class);     
        yield MenuItem::linkToCrud('Race', 'fa fa-title-text', Race::class);
        yield MenuItem::linkToCrud('Arme', 'fa fa-cauldron', Arme::class);
        yield MenuItem::linkToCrud('Personnage', 'fa fa-cauldron', Personnage::class);
    }
}
