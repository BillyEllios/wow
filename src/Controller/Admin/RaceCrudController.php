<?php

namespace App\Controller\Admin;

use App\Entity\Race;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Race::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('faction')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            AssociationField::new('faction'),
            AssociationField::new('personnages'),
            CollectionField::new('personnages')
        ];
    }
}
