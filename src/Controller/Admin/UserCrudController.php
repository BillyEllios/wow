<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
            yield IdField::new('id')
                ->onlyOnIndex();
            yield EmailField::new('email');
            yield TextField::new('password')
                ->hideOnForm();
            $roles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];
            yield ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderAsBadges()
                ->renderExpanded();
            yield AvatarField::new('avatar')
                ->hideOnForm()
                ->formatValue(static function ($value, User $user) {
                    return $user->getAvatar();
                });
            yield ImageField::new('avatar')
                ->setBasePath('avatars/uploads')
                ->setUploadDir('public/avatars/uploads')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
    }
}
