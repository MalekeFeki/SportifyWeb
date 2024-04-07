<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "evenement")]
#[ORM\Entity(repositoryClass: EvenementRepository::class)]

class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "IDevent", type: "integer")]
    private int $idevent;

    #[ORM\Column(name: "NomEv", type: "string")]
    #[Assert\NotBlank(message: "NomEv cannot be empty")]
    private ?string $nomev;

    // #[Assert\Callback(callback: 'validateDateRange')]
    #[ORM\Column(name: "DatedDebutEV", type: "date")]
    // #[Assert\NotBlank(message: "DatedDebutEV cannot be empty")]
    // #[Assert\Date(message: "DatedDebutEV must be a valid date")]
    private ?\DateTimeInterface $dateddebutev;

    #[ORM\Column(name: "DatedFinEV", type: "date")]
    // #[Assert\NotBlank(message: "DatedFinEV cannot be empty")]
    // #[Assert\Date(message: "DatedFinEV must be a valid date")]
    private ?\DateTimeInterface $datedfinev;

    #[ORM\Column(name: "HeureEV", type: "string")]
    // #[Assert\NotBlank(message: "HeureEV cannot be empty")]
    private ?string $heureev;

    #[ORM\Column(name: "DescrptionEv", type: "string")]
    #[Assert\NotBlank(message: "DescrptionEv cannot be empty")]
    private ?string $descrptionev;

    #[ORM\Column(name: "Photo", type: "string")]
    // #[Assert\NotBlank(message: "Photo cannot be empty")]
    private ?string $photo;

    #[ORM\Column(name: "lieu", type: "string")]
    #[Assert\NotBlank(message: "Lieu cannot be empty")]
    private ?string $lieu;

    #[ORM\Column(name: "city", type: "string")]
    #[Assert\NotBlank(message: "City cannot be empty")]
    private ?string $city;

    #[ORM\Column(name: "Tele", type: "string")]
    #[Assert\NotBlank(message: "Tele cannot be empty")]
    #[Assert\Type(type: "digit", message: "Tele must contain only digits")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Tele must be exactly {{ limit }} digits"
    )]
    private ?string $tele;

    #[ORM\Column(name: "Email", type: "string")]
    #[Assert\NotBlank(message: "Email cannot be empty")]
    #[Assert\Email(message: "Email must be a valid email address")]
    private ?string $email;

    #[ORM\Column(name: "FB_link", type: "string")]
    #[Assert\NotBlank(message: "FB_link cannot be empty")]
    private ?string $fbLink;

    #[ORM\Column(name: "IG_link", type: "string")]
    #[Assert\NotBlank(message: "IG_link cannot be empty")]
    private ?string $igLink;

    #[ORM\Column(name: "GenreEvenement", type: "string")]
    #[Assert\NotBlank(message: "GenreEvenement cannot be empty")]
    private ?string $genreevenement;

    #[ORM\Column(name: "typeEV", type: "string")]
    #[Assert\NotBlank(message: "typeEV cannot be empty")]
    private ?string $typeev;

    #[ORM\Column(name: "nombrePersonneInteresse", type: "integer")]
    // #[Assert\NotBlank(message: "nombrePersonneInteresse cannot be empty")]
    #[Assert\Type(type: "integer", message: "nombrePersonneInteresse must be a valid integer")]
    private ?int $nombrepersonneinteresse;

    #[ORM\Column(name: "Capacite", type: "integer")]
    #[Assert\NotBlank(message: "Capacite cannot be empty")]
    #[Assert\Type(type: "integer", message: "Capacite must be a valid integer")]
    private ?int $capacite;

    #[ORM\Column(name: "lat", type: "float")]
    #[Assert\NotBlank(message: "Lat cannot be empty")]
    #[Assert\Type(type: "float", message: "Lat must be a valid float")]
    private ?float $lat;

    #[ORM\Column(name: "lon", type: "float")]
    #[Assert\NotBlank(message: "Lon cannot be empty")]
    #[Assert\Type(type: "float", message: "Lon must be a valid float")]
    private ?float $lon;

    #[ORM\Column(name: "role", type: "string", length: 0, nullable: true)]
    // #[Assert\NotBlank(message: "Role cannot be empty")]
    private ?string $role;

//  /**
//      * @Assert\Callback
//      */

//      #[Assert\Callback]
//      public function validateDateRange(ExecutionContextInterface $context)
//      {
//          if ($this->dateddebutev > $this->datedfinev) {
//              $context->buildViolation('La date de début doit être antérieure à la date de fin.')
//                  ->atPath('dateddebutev')
//                  ->addViolation();
//          }
//      }
    private ?int $heureev_minutes;
    private ?int $heureev_hours;
    public function getHeureevMinutes(): ?string
    {
        return $this->heureev_minutes;
    }

    public function setHeureevMinutes(?string $heureev_minutes): void
    {
        $this->heureev_minutes = $heureev_minutes;
    }
    public function getHeureevHours(): ?string
    {
        return $this->heureev_hours;
    }

    public function setHeureevHours(?string $heureev_hours): void
    {
        $this->heureev_hours = $heureev_hours;
    }
public function getHeureev(): ?string
 {
     return $this->heureev;
 }  public function setHeureev(?string $heureev): void
 {
    if ($heureev !== null) {
        $this->heureev = $heureev;
    } else {
        $this->heureev = null;
    }
 }

     public function getIdevent(): ?int
     {
         return $this->idevent;
     }
 
     public function getNomev(): ?string
     {
         return $this->nomev;
     }
 
     public function getDateddebutev(): ?\DateTimeInterface
     {
         return $this->dateddebutev;
     }
 
     public function getDatedfinev(): ?\DateTimeInterface
     {
         return $this->datedfinev;
     }
     
 
     public function getDescrptionev(): ?string
     {
         return $this->descrptionev;
     }
 
     public function getPhoto(): ?string
     {
         return $this->photo;
     }
 
     public function getLieu(): ?string
     {
         return $this->lieu;
     }
 
     public function getCity(): ?string
     {
         return $this->city;
     }
 
     public function getTele(): ?string
     {
         return $this->tele;
     }
 
     public function getEmail(): ?string
     {
         return $this->email;
     }
 
     public function getFbLink(): ?string
     {
         return $this->fbLink;
     }
 
     public function getIgLink(): ?string
     {
         return $this->igLink;
     }
 
     public function getGenreevenement(): ?string
     {
         return $this->genreevenement;
     }
 
     public function getTypeev(): ?string
     {
         return $this->typeev;
     }
 
     public function getNombrepersonneinteresse(): ?int
     {
         return $this->nombrepersonneinteresse;
     }
 
     public function getCapacite(): ?int
     {
         return $this->capacite;
     }
 
     public function getLat(): ?float
     {
         return $this->lat;
     }
 
     public function getLon(): ?float
     {
         return $this->lon;
     }
 
     
 
     // Setters
 
     public function setNomev(string $nomev): void
     {
         $this->nomev = $nomev;
     }
 
     public function setDateddebutev(?\DateTimeInterface $dateddebutev): void
     {
         $this->dateddebutev = $dateddebutev;
     }
 
     public function setDatedfinev(?\DateTimeInterface $datedfinev): void
     {
         $this->datedfinev = $datedfinev;
     }
 
     
 
     public function setDescrptionev(?string $descrptionev): void
     {
         $this->descrptionev = $descrptionev;
     }
 
     public function setPhoto(?string $photo): void
     {
         $this->photo = $photo;
     }
 
     public function setLieu(?string $lieu): void
     {
         $this->lieu = $lieu;
     }
 
     public function setCity(?string $city): void
     {
         $this->city = $city;
     }
 
     public function setTele(?string $tele): void
     {
         $this->tele = $tele;
     }
 
     public function setEmail(?string $email): void
     {
         $this->email = $email;
     }
 
     public function setFbLink(?string $fbLink): void
     {
         $this->fbLink = $fbLink;
     }
 
     public function setIgLink(?string $igLink): void
     {
         $this->igLink = $igLink;
     }
 
     public function setGenreevenement(?string $genreevenement): void
     {
         $this->genreevenement= $genreevenement;
     }
     public function setTypeev(?string $typeev): void
    {
        $this->typeev = $typeev;
    }

    public function setNombrepersonneinteresse(?int $nombrepersonneinteresse): void
    {
        $this->nombrepersonneinteresse = $nombrepersonneinteresse;
    }

    public function setCapacite(?int $capacite): void
    {
        $this->capacite = $capacite;
    }

    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    public function setLon(?float $lon): void
    {
        $this->lon = $lon;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }
    public function getRole(): ?string
     {
         return $this->role;
     }
     #[ORM\OneToMany(targetEntity: Eventreservation::class, mappedBy: "eventid", orphanRemoval: true, cascade: ["remove"])]
     private $eventreservations;
     public function __construct()
     {
         $this->eventreservations = new ArrayCollection();
     }
     
     public function getEventreservations(): Collection
     {
         return $this->eventreservations;
     }
     
     public function addEventreservation(Eventreservation $eventreservation): self
     {
         if (!$this->eventreservations->contains($eventreservation)) {
             $this->eventreservations[] = $eventreservation;
             $eventreservation->setEventid($this);
         }
     
         return $this;
     }
     
     public function removeEventreservation(Eventreservation $eventreservation): self
     {
         if ($this->eventreservations->removeElement($eventreservation)) {
             // set the owning side to null (unless already changed)
             if ($eventreservation->getEventid() === $this) {
                 $eventreservation->setEventid(null);
             }
         }
     
         return $this;
     }


}
