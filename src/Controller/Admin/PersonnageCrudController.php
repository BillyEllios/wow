<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use App\Services\RaceService;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\EasyAdminExtension\Field\TableField;
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
        yield IdField::new('id')
            ->hideOnForm();
        yield TextField::new('pseudo');
        yield AssociationField::new('classe');
        yield AssociationField::new('race');
        yield AssociationField::new('race', 'Faction')
            ->formatValue(function ($race) {
                return $this->raceService->getFromName($race)->getFaction()->__toString();
            });
        yield AssociationField::new('armes');
        yield TableField::new('armes')
            ->hideOnIndex()
            ->setFields([
                TextField::new('name'),
                TextField::new('type')
            ])
            ->setActions(Actions::new()
                ->add(Crud::PAGE_EDIT, Action::INDEX)
                ->add(Crud::PAGE_EDIT, Action::DELETE)
                ->add(Crud::PAGE_EDIT, Action::DETAIL));
    }
}
