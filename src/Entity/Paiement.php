<?php

namespace App\Entity;
use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ORM\Table(name: "paiement")]
#[ORM\Index(name: "userId", columns: ["userId"])]


class Paiement
{
    #[ORM\Column(name: "idP", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idp;

    #[ORM\Column(name: "Username", type: "string", length: 16, nullable: false)]
    #[Assert\Length(max: 20)]
    private ?string $username;

    #[ORM\Column(name: "Email", type: "string", length: 255, nullable: false)]
    #[Assert\Email]
    private ?string $email;

    #[ORM\Column(name: "Country", type: "string", length: 255, nullable: false)]
    private ?string $country;

    #[ORM\Column(name: "NCB", type: "string", length: 16, nullable: false)]
    #[Assert\Length(max: 16)]
    #[Assert\Type(type: "digit")]
    private ?string $ncb;

    #[ORM\Column(name: "CVC", type: "string", length: 3, nullable: false)]
    #[Assert\Length(exactly: 3)]
    #[Assert\Type(type: "digit")]
    private ?string $cvc;

    #[ORM\Column(name: "ExpDate", type: "date", nullable: false)]
    private ?\DateTimeInterface $expdate;

    #[ORM\Column(name: "PostalCode", type: "integer", nullable: false)]
    #[Assert\Type(type: "digit")]
    #[Assert\Length(max: 4)]
    private ?int $postalcode;

    #[ORM\Column(name: "PromoCode", type: "string", length: 9, nullable: false)]
    #[Assert\Length(exactly: 10)]
    private ?string $promocode;

    #[ORM\Column(name: "Price", type: "float", precision: 10, scale: 0, nullable: false)]
    private ?float $price;

    #[ORM\Column(name: "PDate", type: "date", nullable: false)]
    private ?\DateTimeInterface $pdate;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "userId", referencedColumnName: "id")]

    private ?User $id;

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getNcb(): ?string
    {
        return $this->ncb;
    }

    public function setNcb(?string $ncb): static
    {
        $this->ncb = $ncb;

        return $this;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(?string $cvc): static
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getExpdate(): ?\DateTimeInterface
    {
        return $this->expdate;
    }

    public function setExpdate(?\DateTimeInterface $expdate): static
    {
        $this->expdate = $expdate;

        return $this;
    }

    public function getPostalcode(): ?int
    {
        return $this->postalcode;
    }

    public function setPostalcode(?int $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getPromocode(): ?string
    {
        return $this->promocode;
    }

    public function setPromocode(?string $promocode): static
    {
        $this->promocode = $promocode;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPdate(): ?\DateTimeInterface
    {
        return $this->pdate;
    }

    public function setPdate(?\DateTimeInterface $pdate): static
    {
        $this->pdate = $pdate;

        return $this;
    }

    public function getId(): ?User
    {
        return $this->id;
    }

    public function setId(?User $id): static
    {
        $this->id = $id;

        return $this;
    }
}
