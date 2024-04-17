<?php

namespace App\Entity;

use App\Repository\CoachAdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoachAdminRepository::class)]
#[ORM\Table(name: "coach")]
class CoachAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom', type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\Column(name: 'prenom', type: 'string', length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(name: 'Description', type: 'string', length: 1000, nullable: true)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private ?string $description = null;

    #[ORM\Column(name: 'sexe', type: 'string', length: 10, nullable: true)]
    #[Assert\Choice(choices: ['HOMME', 'FEMME'], message: "Veuillez choisir un sexe valide.")]
    #[Assert\NotNull(message: "Le sexe est obligatoire.")]
    private ?string $sexe = null;

    
    #[ORM\Column(name: 'photo', type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;
    /**
     * @ORM\ManyToMany(targetEntity=CoachClient::class, inversedBy="coachs")
     * @ORM\JoinTable(
     *     name="coach_client_reclamations",
     *     joinColumns={@ORM\JoinColumn(name="coach_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="coach_client_id", referencedColumnName="idR")}
     * )
     */
    private $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    /**
     * @return Collection|CoachClient[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(CoachClient $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->addCoach($this);
        }

        return $this;
    }

    public function removeReclamation(CoachClient $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            $reclamation->removeCoach($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
