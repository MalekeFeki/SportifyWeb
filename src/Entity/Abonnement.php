<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
#[ORM\Table(name: "abonnement")]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idS", type: "integer")]
    private $idS;

    #[ORM\Column(name: "plan", type: "string", length: 50)]
    private $plan;

    #[ORM\Column(name: "duree", type: "integer",length:10)]
    private  int $duree;

    #[ORM\Column(name: "description", type: "string", length: 50)]
    private $description;

    #[ORM\Column(name: "prix", type: "float", length: 50)]
    private float $prix;

    #[ORM\ManyToOne(targetEntity: Salle::class)]
    #[ORM\JoinColumn(name: "idS", referencedColumnName: "idS")]
    private $salle;

    public function getIdS(): int
    {
        return $this-> idS;
    }

    public function setIdS(int $idS): Abonnement
    {
        $this->idS = $idS;
        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }
}
