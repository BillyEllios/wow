<?php 

namespace App\DataFixtures;

use App\Entity\Faction;
use App\Services\FactionService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FactionFixtures extends Fixture {
    
    public function __construct()
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faction = [];
        $factions = ['Alliance','Horde'];
        for ($i=0; $i<2; $i++) {
            $faction[] = (new Faction())
            ->setName($factions[$i]);
        }

        $this->persist($manager, ...$faction);
    }

    private function persist(ObjectManager $manager, ...$objects)
    {
        foreach ($objects as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}