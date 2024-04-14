<?php

namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as assert;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "utilisateur")] 
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface,PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $cin = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Length(
        min : 8,
       max :8,
       exactMessage : "Le numéro de téléphone doit avoir exactement {{ limit }} chiffres",
        normalizer : "trim"
    )]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Length(
        min : 8,
       max :255,
       exactMessage : "Le mot de passe doit avoir minimum {{ limit }} caractères",
        normalizer : "trim"
    )]
    private ?string $mdp = null;
private $roles=[];
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $role = null;

   /**
 * @ORM\Column(length=255, nullable=true)
 */
private ?string $imageName;

/**
 * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName")
 */
private ?File $imageFile;


    // Getters and setters for $imageFile
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    // Getter and setter for $imageName
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
    // Implémentation de UserInterface
    public function getUserIdentifier(): ?string
    {
        return (string) $this->email;
    }

   

    
    public function getPassword(): ?string
    {
        return $this->mdp;
    }
    
    public function setPassword(string $mdp): static
    {
        $this->mdp= $mdp;

        return $this;
    }

    // Implémentation de UserInterface
    public function getSalt(): ?string
    {

        return null;
    }

  
    public function eraseCredentials():void
    {
       
    }
    public function getUsername(): ?string
{
    return (string) $this->email;
}
public function getRoles(): array
{
    $roles= $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';
    return array_unique($roles);
}
public function setRoles(array $roles): static
{
    $this->roles = $roles;
    return $this;
} 
   private $showPassword;

    public function getShowPassword(): ?bool
    {
        return $this->showPassword;
    }

    public function setShowPassword(bool $showPassword): self
    {
        $this->showPassword = $showPassword;

        return $this;
    }
private $authCode;

// [...]

public function isEmailAuthEnabled(): bool
{
    return true; // This can be a persisted field to switch email code authentication on/off
}

public function getEmailAuthRecipient(): string
{
    return $this->email;
}

public function getEmailAuthCode(): string
{
    if (null === $this->authCode) {
        throw new \LogicException('The email authentication code was not set');
    }

    return $this->authCode;
}

public function setEmailAuthCode(string $authCode): void
{
    $this->authCode = $authCode;
}
/**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    // [...]

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return null !== $this->googleAuthenticatorSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->prenom;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }
}