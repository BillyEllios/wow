<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClasseFixtures extends Fixture {
    
    public function __construct()
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $classe = [];
        $classes = ['Guerrier','Paladin','Chasseur','Voleur','Prêtre','Chaman','Mage','Démoniste','Moine','Druide','Chasseur de démons','Chevalier de la mort'];
        for ($i=0; $i<count($classes); $i++) {
            $classe[] = (new Classe())
            ->setName($classes[$i]);
        }

        $this->persist($manager, ...$classe);
    }

    private function persist(ObjectManager $manager, ...$objects)
    {
        foreach ($objects as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}