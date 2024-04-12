<?php

namespace App\Entity;

use App\Repository\CoachAdminRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoachAdminRepository::class)]
#[ORM\Table(name: "coach")]
class CoachAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCo')]
    private ?int $idCo = null;

    #[ORM\Column(name: 'NomCo', type: 'string', length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 3, minMessage: "nombre des caractére est inférieur à  3  .")]
    private ?string $nomCo = null;

    #[ORM\Column(name: 'PrenomCo', type: 'string', length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(min: 3, minMessage: "nombre des caractére est inférieur à  3  .")]
    private ?string $prenomCo = null;

    #[ORM\Column(name: 'Description', type: 'string', length: 1000, nullable: true)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
     private ?string $description = null;

     #[ORM\Column(name: 'sexe', type: 'string', length: 10, nullable: true)]
     #[Assert\Choice(choices: ['HOMME', 'FEMME'], message: "Veuillez choisir un sexe valide.")]
     #[Assert\NotNull(message: "Le sexe est obligatoire.")]
     private ?string $sexe = null;

    public function getId(): ?int
    {
        return $this->idCo;
    }

    public function getNomCo(): ?string
    {
        return $this->nomCo;
    }

    public function setNomCo(?string $nomCo): self
    {
        $this->nomCo = $nomCo;

        return $this;
    }

    public function getPrenomCo(): ?string
    {
        return $this->prenomCo;
    }

    public function setPrenomCo(?string $prenomCo): self
    {
        $this->prenomCo = $prenomCo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }
}
