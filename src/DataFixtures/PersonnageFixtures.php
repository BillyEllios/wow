<?php 

namespace App\DataFixtures;

use App\Entity\Personnage;
use App\Services\ClasseService;
use App\Services\PersonnageService;
use App\Services\RaceService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonnageFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private PersonnageService $personnageService,
        private ClasseService $classeService,
        private RaceService $raceService)
    {
        
    }

    public function getDependencies() {
        return [
            ClasseFixtures::class,
            RaceFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $personnage = [];
        for ($i=0; $i<15; $i++) {
            $personnage[] = (new Personnage())
                ->setPseudo($this->personnageService->getPersonnage())
                ->setClasse($this->classeService->getClasse())
                ->setRace($this->raceService->getRace());
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