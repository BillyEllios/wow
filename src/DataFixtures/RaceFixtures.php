<?php 

namespace App\DataFixtures;

use App\Entity\Race;
use App\Services\FactionService;
use App\Services\RaceService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private FactionService $factionService
    )
    {
        
    }

    public function getDependencies() {
        return [
            FactionFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $raceEntities = [];
        // $races = ['Humain','Nain','Elfe de la nuit','Gnome','Draeneï','Worgen','Pandaren','Elfe du vide','Draeneï sancteforge','Nain Sombrefer','Kultirassien','Mécagnome','Orc','Mort-vivant','Tauren','Troll','Elfe de sang','Gobelin','Sacrenuit','Tauren de Haut-Roc','Orc Mag\'har','Troll zandalari','Vulpérin'];
        $races = ['Humain' => 'Alliance', 'Pandaren' => 'Alliance', 'Nain' => 'Alliance', 'Elfe de la nuit' => 'Alliance', 'Gnome' => 'Alliance','Draeneï' => 'Alliance', 'Worgen' => 'Alliance', 'Elfe du vide' => 'Alliance', 'Draeneï sancteforge' => 'Alliance', 'Nain Sombrefer' => 'Alliance', 'Kultirassien' => 'Alliance', 'Mécagnome' => 'Alliance', 'Orc' => 'Horde','Orc Mag\'har' => 'Horde', 'Mort-vivant' => 'Horde', 'Tauren' => 'Horde','Tauren de Haut-Roc' => 'Horde', 'Elfe de sang' => 'Horde', 'Troll' => 'Horde', 'Troll zandalari' => 'Horde', 'Sacrenuit' => 'Horde','Vulpérin' => 'Horde', 'Gobelin' => 'Horde' ];
        foreach ($races as $race => $faction) {
            $raceEntities[] = (new Race())
                ->setName($race)
                ->setFaction($this->factionService->getFromName($faction));
        }

        $this->persist($manager, ...$raceEntities);
    }

    private function persist(ObjectManager $manager, ...$objects)
    {
        foreach ($objects as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}