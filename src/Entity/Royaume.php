<?php

namespace App\Entity;

use App\Repository\RoyaumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoyaumeRepository::class)]
class Royaume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'royaume', targetEntity: Monstre::class)]
    private Collection $monstres;

    public function __construct()
    {
        $this->monstres = new ArrayCollection();
    }
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Monstre>
     */
    public function getMonstres(): Collection
    {
        return $this->monstres;
    }

    public function addMonstre(Monstre $monstre): static
    {
        if (!$this->monstres->contains($monstre)) {
            $this->monstres->add($monstre);
            $monstre->setRoyaume($this);
        }

        return $this;
    }

    public function removeMonstre(Monstre $monstre): static
    {
        if ($this->monstres->removeElement($monstre)) {
            // set the owning side to null (unless already changed)
            if ($monstre->getRoyaume() === $this) {
                $monstre->setRoyaume(null);
            }
        }

        return $this;
    }
}
