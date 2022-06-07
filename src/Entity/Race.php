<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Faction::class, inversedBy: 'races')]
    #[ORM\JoinColumn(nullable: false)]
    private $faction;

    #[ORM\Column(type: 'string', length: 32)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Classe::class, mappedBy: 'races')]
    private $classes;

    #[ORM\OneToMany(mappedBy: 'races', targetEntity: Personnage::class)]
    private $personnages;

    #[ORM\ManyToMany(targetEntity: Classe::class, mappedBy: 'races')]
    private $classees;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->personnages = new ArrayCollection();
        $this->classees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setFaction(?Faction $faction): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->addRace($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            $class->removeRace($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Personnage>
     */
    public function getPersonnages(): Collection
    {
        return $this->personnages;
    }

    public function addPersonnage(Personnage $personnage): self
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages[] = $personnage;
            $personnage->setRaces($this);
        }

        return $this;
    }

    public function removePersonnage(Personnage $personnage): self
    {
        if ($this->personnages->removeElement($personnage)) {
            // set the owning side to null (unless already changed)
            if ($personnage->getRaces() === $this) {
                $personnage->setRaces(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClassees(): Collection
    {
        return $this->classees;
    }

    public function addClassee(Classe $classee): self
    {
        if (!$this->classees->contains($classee)) {
            $this->classees[] = $classee;
            $classee->addRace($this);
        }

        return $this;
    }

    public function removeClassee(Classe $classee): self
    {
        if ($this->classees->removeElement($classee)) {
            $classee->removeRace($this);
        }

        return $this;
    }
}
