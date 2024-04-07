<?php

namespace App\Entity;

use App\Repository\EventreservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "eventreservation")]
#[ORM\Index(name: "eventId", columns: ["eventId"])]
#[ORM\Entity(repositoryClass: EventreservationRepository::class)]

class Eventreservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "reservationId", type: "integer", nullable: false)]
    private int $reservationid;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "nom cannot be empty")]
    private string $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "prenom cannot be empty")]
    private string $prenom;

    #[ORM\Column(name: "cin", type: "integer", nullable: true)]
    #[Assert\NotBlank(message: "cin cannot be empty")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Tele must be exactly {{ limit }} digits"
    )]
    private ?int $cin;

    #[ORM\Column(name: "Email", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "email cannot be empty")]
    #[Assert\Email(message: "Email must be a valid email address")]

    private ?string $email;

    #[ORM\Column(name: "num_tele", type: "integer", nullable: true)]
    #[Assert\NotBlank(message: "numTele cannot be empty")]
    #[Assert\Type(type: "digit", message: "Tele must contain only digits")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Tele must be exactly {{ limit }} digits"
    )]
    private ?int $numTele;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: "eventId", referencedColumnName: "IDevent")]
    private ?Evenement $eventid;

    // Getters

    public function getReservationid(): ?int
    {
        return $this->reservationid;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getNumTele(): ?int
    {
        return $this->numTele;
    }

    public function getEventid(): ?Evenement
    {
        return $this->eventid;
    }

    // Setters

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setCin(?int $cin): void
    {
        $this->cin = $cin;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setNumTele(?int $numTele): void
    {
        $this->numTele = $numTele;
    }

    public function setEventid(?Evenement $eventid): void
    {
        $this->eventid = $eventid;
    }
    public function setEventidbyevent(?int $eventid): void
    {
        $this->eventid = $eventid;
    }
}
