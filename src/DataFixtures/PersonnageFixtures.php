<?php 

namespace App\DataFixtures;

use App\Entity\Personnage;
use App\Services\PersonnageService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonnageFixtures extends Fixture
{
    public function __construct(private PersonnageService $personnageService)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $personnage = [];
        for ($i=0; $i<15; $i++) {
            $personnage[] = (new Personnage())
                ->setPseudo($this->personnageService->getPersonnage());
        }

        $this->persist($manager, ...$personnage);
    }

    private function persist(ObjectManager $manager, ...$objects)
    {
        foreach ($objects as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}