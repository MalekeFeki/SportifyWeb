<?php

namespace App\Entity;

use App\Repository\CoachClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\ManyToMany(targetEntity=CoachAdmin::class, mappedBy="reclamations")
     */
    private $coachs;

    public function __construct()
    {
        $this->coachs = new ArrayCollection();
    }

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

    /**
     * @return Collection|CoachAdmin[]
     */
    public function getCoachs(): Collection
    {
        return $this->coachs;
    }

    public function addCoach(CoachAdmin $coach): self
    {
        if (!$this->coachs->contains($coach)) {
            $this->coachs[] = $coach;
            $coach->addReclamation($this);
        }

        return $this;
    }

    public function removeCoach(CoachAdmin $coach): self
    {
        if ($this->coachs->removeElement($coach)) {
            $coach->removeReclamation($this);
        }

        return $this;
    }

    // Méthode getComment() ajoutée
    public function getComment(): ?string
    {
        return $this->commentaire;
    }
}
