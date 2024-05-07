<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: "salle")]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idS", type: "integer", nullable: false)]
    private $idS;

    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    private $nom;

    #[ORM\Column(name: "adresse", type: "string", length: 100, nullable: false)]
    private $adresse;

    #[ORM\Column(name: "region", type: "string", length: 50, nullable: false)]
    private $region;

    #[ORM\Column(name: "options", type: "simple_array", length: 0, nullable: false)]
    private $options;

    #[ORM\Column(name: "imageSalle", type: "string", length: 255, nullable: true)]
    private $imageSalle;

    public function __construct()
    {
        // Initialize options as an empty array
        $this->options = [];
    }

    public function getIdS(): ?int
    {
        return $this->idS;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getImageSalle(): ?string
    {
        return $this->imageSalle;
    }

    public function setImageSalle(?string $imageSalle): static
    {
        $this->imageSalle = $imageSalle;

        return $this;
    }
}
