<?php

namespace App\Controller\Admin;

use App\Entity\Arme;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArmeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Arme::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('type')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('type'),
            AssociationField::new('personnage'),
            AssociationField::new('classes')
        ];
    }
}
