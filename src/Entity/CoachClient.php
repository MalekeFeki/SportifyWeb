<?php

namespace App\Entity;

use App\Repository\CoachClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoachClientRepository::class)]
#[ORM\Table(name: "reclamation")]
class CoachClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idR")]
    private ?int $idR = null;

    #[ORM\Column(name: 'commentaire', type: 'string', length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le commentaire ne peut pas être vide.")]
    #[Assert\Length(min: 3, maxMessage: "Le commentaire est inférieur à 3 caractères.")]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->idR;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
