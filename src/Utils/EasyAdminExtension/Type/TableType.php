<?php

declare(strict_types=1);

namespace App\Utils\EasyAdminExtension\Type;

use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\ActionCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionDto;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableType extends AbstractType
{
    private ContainerBagInterface $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'fields',
        ]);
        $resolver->setDefaults([
            'disabled' => true,
            'entitiesCollection' => new ArrayCollection(),
            'globalActions' => ActionCollection::new([])->all(),
            'modalDeleteContent' => '',
            'modalDeleteTitle' => ''
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
    }

    public function getParent(): string
    {
        return FormType::class;
    }
}
