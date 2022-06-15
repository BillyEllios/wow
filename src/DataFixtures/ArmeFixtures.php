<?php

namespace App\DataFixtures;

use App\Entity\Arme;
use App\Services\ArmeService;
use App\Services\PersonnageService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArmeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ArmeService $armeService,
        private PersonnageService $personnageService)
    {
        
    }

    public function getDependencies() {
        return [
            PersonnageFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $arme = [];
        for ($i=0; $i<50; $i++) {
            $arme[] = (new Arme())
                ->setName($this->armeService->getNArme())
                ->setType($this->armeService->getTArme())
                ->setPersonnage($this->personnageService->getPersonnages());
        }

        $this->persist($manager, ...$arme);
    }

    private function persist(ObjectManager $manager, ...$objects)
    {
        foreach ($objects as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}