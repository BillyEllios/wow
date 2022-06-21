<?php

declare(strict_types=1);

namespace App\Utils\EasyAdminExtension\Field;

use App\Utils\EasyAdminExtension\Type\TableType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

/**
 * Champ permettant dans EasyAdmin d'afficher sous forme de liste les entités associées à l'entité affichée (page de détail uniquement).
 * Il est possible d'afficher n'importe quelle champ ou action comme dans une page d'index classique de EasyAdmin.
 * L'utilisation de cette classe implique nécessairement d'appeler la fonction "processResponseParameters" dans la fonction "configureResponseParameters d'un controller CRUD d'EasyAdmin".
 * @see processResponseParameters
 * @author Pierre-François Giraud
 */
final class TableField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_QUERY_BUILDER_CALLABLE = 'queryBuilderCallable';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('field/table.html.twig')
            ->setFormType(TableType::class)
            ->addCssClass('table-field')
            ->setCustomOption('actions', Actions::new())
            ->setCustomOption('global_actions', Actions::new())
            ->setFormTypeOptions([
                'disabled' => true,
                'modalDeleteContent' => 'delete_modal.content',
                'modalDeleteTitle' => 'delete_modal.title'
            ]);
    }

    public function setFields(array $fields = []) {
        $this->setFormTypeOption('fields', $fields);
        return $this;
    }

    public function setActions(Actions $actions) {
        $this->setCustomOption('actions', $actions);
        return $this;
    }

    public function setGlobalActions(Actions $actions) {
        $this->setCustomOption('global_actions', $actions);
        return $this;
    }

    public function setDeleteModalText(string $title, string $content) {
        $this->setFormTypeOption('modalDeleteTitle', $title);
        $this->setFormTypeOption('modalDeleteContent', $content);
        return $this;
    }

    public function setQueryBuilder(\Closure $queryBuilderCallable): self
    {
        $this->setCustomOption(self::OPTION_QUERY_BUILDER_CALLABLE, $queryBuilderCallable);

        return $this;
    }

    private static function getEntityTypeFromPropertyPath(string $propertyPath, string $fqcn, EntityManagerInterface $entityManager): string {
        $subEntityType = $fqcn;
        foreach(explode('.', $propertyPath) as $propertyName) {
            $subEntityType = $entityManager->getClassMetadata($subEntityType)->getAssociationTargetClass($propertyName);
        }
        return $subEntityType;
    }

    private static function getMappedByTargetFieldFromPropertyPath(string $propertyPath, string $fqcn, EntityManagerInterface $entityManager): string {
        $inverseProperties = [];
        foreach(explode('.', $propertyPath) as $propertyName) {
            $inverseProperties[] =  $entityManager->getClassMetadata($fqcn)->getAssociationMappedByTargetField($propertyName) ?? $entityManager->getClassMetadata($fqcn)->getAssociationMapping($propertyName)['inversedBy'];
            $fqcn = $entityManager->getClassMetadata($fqcn)->getAssociationTargetClass($propertyName);
        }
        return join('.', array_reverse($inverseProperties));
    }

    private static function setJoinsToqueryBuilderFromPropertyPath(string $propertyPath, string $fqcn, EntityManagerInterface $entityManager) {
        $inverseProperties = [];
        foreach(explode('.', $propertyPath) as $propertyName) {
            $inverseProperties[] =  $entityManager->getClassMetadata($fqcn)->getAssociationMappedByTargetField($propertyName) ?? $entityManager->getClassMetadata($fqcn)->getAssociationMapping($propertyName)['inversedBy'];
            $fqcn = $entityManager->getClassMetadata($fqcn)->getAssociationTargetClass($propertyName);
        }
        return join('.', array_reverse($inverseProperties));
    }

    private static function getSubEntitiesCollection(EntityDto $entityDto, string $propertyPath, array $sort, EntityManagerInterface $entityManager, EntityFactory $entityFactory, $queryBuilder) {
        $subEntityType = self::getEntityTypeFromPropertyPath($propertyPath, $entityDto->getFqcn(), $entityManager);
        $inversePropertyPath = self::getMappedByTargetFieldFromPropertyPath($propertyPath, $entityDto->getFqcn(), $entityManager);
        $inverseProperties = explode('.', $inversePropertyPath);

        $name = 'entity';
        $qb = $entityManager->createQueryBuilder()->select($name)->from($subEntityType, 'entity');
        if($sort != null) {
            foreach($sort as $property => $direction) {
                if($entityManager->getClassMetadata($subEntityType)->hasField($property)
                || $entityManager->getClassMetadata($subEntityType)->hasAssociation($property))
                    $qb = $qb->orderBy($name.'.'.$property, $direction);
            }
        }   

        $subEntityType2 = $subEntityType; // On refait le chemin dans le sens inverse

        foreach(array_slice($inverseProperties, 0, -1) as $propertyName) {
            $subEntityType2 = $entityManager->getClassMetadata($subEntityType2)->getAssociationTargetClass($propertyName);
            $qb = $qb->leftJoin($subEntityType2, $propertyName, Join::WITH, $propertyName.'.id = '.$name.'.'.$propertyName);
            $name = $propertyName;
        }

        // Check if there
        $nextName = array_slice($inverseProperties, -1)[0];
        if($entityManager->getClassMetadata($subEntityType2)->isCollectionValuedAssociation($nextName) && isset($propertyName)) {
            $qb = $qb->leftJoin($entityDto->getFqcn(), $nextName, Join::WITH, $nextName.'.'.$propertyName.' = '.$propertyName.'.id')
                    ->where($nextName.'.id = :fk');
        } else if($entityManager->getClassMetadata($subEntityType2)->isCollectionValuedAssociation($nextName) && $entityManager->getClassMetadata($subEntityType2)->isAssociationInverseSide($nextName)) {
            $qb = $qb->where(':fk MEMBER OF '.$name.'.'.array_slice($inverseProperties, -1)[0]);
        } else {
            $qb = $qb->where($name.'.'.array_slice($inverseProperties, -1)[0].' = :fk');

        }
        if ($queryBuilder) {
            $queryBuilder($qb);
        }
        $subEntities = $qb->setParameter('fk', $entityDto->getPrimaryKeyValue())
           ->getQuery()->getResult();

        $subEntityClassMetadata = $entityManager->getClassMetadata($subEntityType);
        $subEntityDto = new EntityDto($subEntityType, $subEntityClassMetadata);                
        return $entityFactory->createCollection($subEntityDto, $subEntities);
    }

    public static function processResponseParameters(KeyValueStore $responseParameters, EntityManagerInterface $entityManager, EntityFactory $entityFactory, ?array $sort = []) : KeyValueStore {
        $pageName = $responseParameters->get('pageName');
        
        if($pageName != Crud::PAGE_DETAIL) return $responseParameters;
        
        foreach($responseParameters->get('entity')->getFields() as $field) {
            if($field->getFieldFqcn() == TableField::class) {
                $subEntitiesCollection = self::getSubEntitiesCollection($responseParameters->get('entity'), $field->getProperty(), $sort, $entityManager, $entityFactory, $field->getCustomOption(self::OPTION_QUERY_BUILDER_CALLABLE));
                // Set field values
                $entityFactory->processFieldsForAll($subEntitiesCollection, FieldCollection::new($field->getFormTypeOption('fields')));
                // Set global actions
                $globalActions = $entityFactory->processActionsForAll($subEntitiesCollection, $field->getCustomOption('global_actions')->getAsDto($pageName))->getGlobalActions();
                // Set entity actions
                $actionsConfig = $field->getCustomOption('actions')->getAsDto($pageName);
                $entityFactory->processActionsForAll($subEntitiesCollection, $actionsConfig);
                $field->setFormTypeOption('globalActions', $globalActions);
                // Set collection
                $field->setFormTypeOption('entitiesCollection', $subEntitiesCollection);
            }
        }
        return $responseParameters;
    }
}