<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalleRepository;
#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: "salle")]


class Salle
{
    #[ORM\Column(name: "idS", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $ids=1;

    #[ORM\Column(name: "nomS", type: "string", length: 50, nullable: false)]
    private ?string $noms;

    #[ORM\Column(name: "addressed", type: "string", length: 50, nullable: false)]
    private ?string $addressed;

    #[ORM\Column(name: "region", type: "string", length: 50, nullable: false)]
    private ?string $region;

    #[ORM\Column(name: "options", type: "string", length: 0, nullable: false)]
    private ?string $options;

    public function getIds(): ?int
    {
        return $this->ids;
    }

    public function getNoms(): ?string
    {
        return $this->noms;
    }

    public function setNoms(?string $noms): static
    {
        $this->noms = $noms;
        return $this;
    }

    public function getAddressed(): ?string
    {
        return $this->addressed;
    }

    public function setAddressed(?string $addressed): static
    {
        $this->addressed = $addressed;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;
        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): static
    {
        $this->options = $options;
        return $this;
    }
}
