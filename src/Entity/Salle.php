<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    private $imagesalle;

    public function __construct()
    {
        // Initialize options as an empty array
        $this->options = [];
    }

    public function getIds(): ?int
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

    public function getImagesalle(): ?string
    {
        return $this->imagesalle;
    }

    public function setImagesalle(?string $imagesalle): static
    {
        $this->imagesalle = $imagesalle;

        return $this;
    }

}
