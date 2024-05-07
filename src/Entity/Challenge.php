<?php

namespace App\Entity;

use App\Repository\ChallengeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
#[ORM\Table(name: "challenge")]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idC", type: "integer", nullable: false)]
    private int $idc;

    #[ORM\Column(name: "Nom", type: "string", length: 20, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "difficulty", type: "string", length: 0, nullable: true)]
    private ?string $difficulty;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    public function getIdc(): ?int
    {
        return $this->idc;
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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
