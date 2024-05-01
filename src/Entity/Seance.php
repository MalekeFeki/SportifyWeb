<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "seance")]

class Seance
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idseance", type: "integer")]
    private  $idseance;

    #[Doctrine\ORM\Mapping\Column(type: "string", length: 50, nullable: false)]
    #[ORM\Column(name: "nomseance", type: "string")]
    private $nomseance;

    #[Doctrine\ORM\Mapping\Column(type: "time", nullable: false)]
    #[ORM\Column(name: "debut", type: "time")]
    private $debut;

    #[Doctrine\ORM\Mapping\Column(type: "time", nullable: false)]
    #[ORM\Column(name: "fin", type: "time")]
    private $fin;

    /**
    * @ORM\Column(type="date")
    */
    private $dates;

    #[Doctrine\ORM\Mapping\ManyToOne(targetEntity: Salle::class)]
    #[Doctrine\ORM\Mapping\JoinColumn(name: "idS", referencedColumnName: "idS")]
    private ?Salle $idS;
    // private $salle;

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
    return $this->idS;
}

public function setSalle(?Salle $salle): self
{
    $this->idS = $salle;

    return $this;
}


    public function getIdS(): self
{
    return $this->idS;
}

public function setIdS(self $idS): void
{
    $this->idS = $idS;

    
}
}