<?php

namespace App\Controller\Admin;

use App\Entity\Arme;
use App\Entity\Classe;
use App\Entity\Faction;
use App\Entity\Personnage;
use App\Entity\Race;
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
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 
        return $this->redirect($this->adminUrlGenerator->setController(RaceCrudController::class)->generateUrl());
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wow');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Entities', 'fa fa-hat-wizard');

        yield MenuItem::linkToCrud('Faction', 'fa fa-title-text', Faction::class);
        yield MenuItem::linkToCrud('Classe', 'fa fa-cauldron', Classe::class);     
        yield MenuItem::linkToCrud('Race', 'fa fa-title-text', Race::class);
        yield MenuItem::linkToCrud('Arme', 'fa fa-cauldron', Arme::class);
        yield MenuItem::linkToCrud('Personnage', 'fa fa-cauldron', Personnage::class);
    }
}
