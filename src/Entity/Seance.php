<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
#[ORM\Table(name: "seance")]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idseance", type: "integer")]
    private $idseance;

    #[ORM\Column(name: "nomseance", type: "string", length: 50)]
    private $nomseance;

    #[ORM\Column(name: "debut", type: "time")]
    private $debut;

    #[ORM\Column(name: "fin", type: "time")]
    private $fin;

    #[ORM\Column(name: "dates", type: "date")]
    private $dates;

    #[ORM\ManyToOne(targetEntity: Salle::class)]
    #[ORM\JoinColumn(name: "idS", referencedColumnName: "idS")]
    private $salle;

    public function getIdseance(): ?int
    {
        return $this->idseance;
    }

    public function getNomseance(): ?string
    {
        return $this->nomseance;
    }

    public function setNomseance(string $nomseance): self
    {
        $this->nomseance = $nomseance;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getDates(): ?\DateTimeInterface
    {
        return $this->dates;
    }

    public function setDates(?\DateTimeInterface $dates): self
    {
        $this->dates = $dates;

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
