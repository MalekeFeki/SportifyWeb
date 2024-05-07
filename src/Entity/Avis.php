<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\Table(name: "avis")]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idA", type: "integer", nullable: false)]
    private int $ida;

    #[ORM\Column(name: "type", type: "string", length: 0, nullable: false)]
    private string $type;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private string $description;

    public function getIda(): ?int
    {
        return $this->ida;
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
