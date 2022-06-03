<?php

namespace App\DataFixtures;

use App\Entity\Arme;
use App\Services\ArmeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArmeFixtures extends Fixture
{
    public function __construct(private ArmeService $armeService)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $arme = [];
        for ($i=0; $i<50; $i++) {
            $arme[] = (new Arme())
            ->setName($this->armeService->getNArme())
            ->setType($this->armeService->getTArme());
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