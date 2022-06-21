<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use App\Services\RaceService;
use App\Utils\EasyAdminExtension\Field\TableField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpParser\ErrorHandler\Collecting;

class PersonnageCrudController extends AbstractCrudController 
{
    public function __construct(
        private RaceService $raceService, 
        private EntityManagerInterface $entityManager, 
        private EntityFactory $entityFactory) {

    }

    public static function getEntityFqcn(): string
    {
        return Personnage::class;
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        return TableField::processResponseParameters(
            $responseParameters, 
            $this->entityManager, 
            $this->entityFactory);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('race')
            ->add('classe')
            ->add('armes')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('pseudo'),
            AssociationField::new('classe'),
            AssociationField::new('race'),
            AssociationField::new('race', 'Faction')
                ->formatValue(function ($race) {
                    return $this->raceService->getFromName($race)->getFaction()->__toString();
                }),
            AssociationField::new('armes'),
            TableField::new('armes')
                ->setFields([
                    TextField::new('name'),
                    TextField::new('type')
                ])
                ->setActions(Actions::new()
                    ->add(Crud::PAGE_DETAIL, Action::EDIT)
                    ->add(Crud::PAGE_DETAIL, Action::DETAIL))
        ];
    }
}
