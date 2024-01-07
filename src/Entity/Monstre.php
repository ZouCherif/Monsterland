<?php

namespace App\Entity;

use App\Repository\MonstreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MonstreRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Monstre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
     /**
     * @Assert\NotBlank(message="Le nom ne peut pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Le nom ne doit contenir que des lettres."
     * )
     */
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(['Zombie', 'Vampire', 'Orque', 'Titan', 'Ogre', 'Lutin'], message: "Le type doit être l'un des suivants : Zombie, Vampire, Orque, Titan, Ogre, Lutin")]
    private ?string $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: "La puissance doit être comprise entre 0 et 100",
        invalidMessage: "Veuillez entrer un nombre entier"
    )]
    private ?int $puissance = null;

    #[ORM\Column]
    #[Assert\GreaterThan(
        value: 0,
        message: "La taille doit être un entier supérieur à zéro"
    )]
    private ?int $taille = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'monstres')]
    private ?Royaume $royaume = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function corrigeNom() {
        $this->nom = ucfirst(strtolower($this->nom));
    }

    public function getRoyaume(): ?Royaume
    {
        return $this->royaume;
    }

    public function setRoyaume(?Royaume $royaume): static
    {
        $this->royaume = $royaume;

        return $this;
    } 
    public function __toString() {
        return $this->getNom().'-'.$this->getType().'.'.$this->getPuissance().'.'.$this.getTaille();
       }
       
}
