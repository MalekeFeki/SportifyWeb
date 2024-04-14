<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdhesionRepository;
use App\Entity\User ;
#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
#[ORM\Table(name: "adhesion")] 
#[ORM\Index(name: "userId", columns: ["userId"])] 
#[ORM\Index(name: "gymId", columns: ["gymId"])]

class Adhesion
{
    #[ORM\Column(name: "idA", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $ida;

    #[ORM\Column(name: "Username", type: "string", length: 255, nullable: false)]
    private ?string $username;

    #[ORM\Column(name: "Gymname", type: "string", length: 255, nullable: false)]
    private ?string $gymname;

    #[ORM\Column(name: "TypeA", type: "string", length: 0, nullable: false)]
    private ?string $typea;

    #[ORM\Column(name: "Price", type: "float", precision: 10, scale: 0, nullable: false)]
    private ?float $price;

    #[ORM\Column(name: "DebutA", type: "date", nullable: false)]
    private ?\DateTimeInterface $debuta;

    #[ORM\Column(name: "FinA", type: "date", nullable: false)]
    private ?\DateTimeInterface $fina;

    #[ORM\ManyToOne(targetEntity: "Salle")]
    #[ORM\JoinColumn(name: "gymId", referencedColumnName: "idS")]

    private ?Salle $gymid;

    #[ORM\ManyToOne(targetEntity: User::Class)]
    #[ORM\JoinColumn(name: "userId", referencedColumnName: "id")]

    private ?User $id;

    public function getIda(): ?int
    {
        return $this->ida;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getGymname(): ?string
    {
        return $this->gymname;
    }

    public function setGymname(?string $gymname): static
    {
        $this->gymname = $gymname;

        return $this;
    }

    public function getTypea(): ?string
    {
        return $this->typea;
    }

    public function setTypea(?string $typea): static
    {
        $this->typea = $typea;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDebuta(): ?\DateTimeInterface
    {
        return $this->debuta;
    }

    public function setDebuta(?\DateTimeInterface $debuta): static
    {
        $this->debuta = $debuta;

        return $this;
    }

    public function getFina(): ?\DateTimeInterface
    {
        return $this->fina;
    }

    public function setFina(?\DateTimeInterface $fina): static
    {
        $this->fina = $fina;

        return $this;
    }

    public function getGymid(): ?Salle
    {
        return $this->gymid;
    }

    public function setGymid(?Salle $gymid): static
    {
        $this->gymid = $gymid;

        return $this;
    }

    public function getId(): ?User
    {
        return $this->id;
    }

    public function setId(?User $id): static
    {
        $this->id = $id;

        return $this;
    }

}
