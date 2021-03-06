<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable()
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Un utilisateur avec le même mail existe déjà."
 * )
 */
class User implements UserInterface
{
    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *     min = 1,
     *     max= 60,
     *     minMessage= "Votre nom doit contenir au moins {{ limit }} caractères",
     *     maxMessage= "Votre nom ne peut contenir plus de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿœ' ]+$/",
     *     match=true,
     *     message="Merci de mettre uniquement que des lettres"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *     min = 1,
     *     max= 60,
     *     minMessage= "Le prénom doit contenir au moins {{ limit }} caractères",
     *     maxMessage= "Le prénom ne peut contenir plus de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿœ-]+$/",
     *     match=true,
     *     message="Merci de mettre uniquement que des lettres"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Regex(
     *     pattern="/^((\+|00)32\s?|0)4(60|[789]\d)(\s?\d{2}){3}$/",
     *     message="Ce numéro de GSM est incorrect"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastLogAt;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class, inversedBy="users")
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ranking;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenValidity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $verifiedAt;

    /**
     * @ORM\ManyToMany(targetEntity=MatchList::class, mappedBy="user")
     */
    private $matchLists;

    /**
     * @ORM\ManyToMany(targetEntity=Stage::class, mappedBy="user")
     */
    private $stages;

    /**
     * @ORM\OneToMany(targetEntity=MatchCancellation::class, mappedBy="user")
     */
    private $matchCancellations;

    public function __construct()
    {
        $this->matchLists = new ArrayCollection();
        $this->stages = new ArrayCollection();
        $this->matchCancellations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLogAt(): ?\DateTimeInterface
    {
        return $this->lastLogAt;
    }

    public function setLastLogAt(\DateTimeInterface $lastLogAt): self
    {
        $this->lastLogAt = $lastLogAt;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @see UserInterface
     */
    public function getUsername()
    {
        $username = $this->firstname . ' ' . $this->lastname;
        return (string)$username;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getTeam(): ?Teams
    {
        return $this->team;
    }

    public function setTeam(?Teams $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getRanking(): ?string
    {
        return $this->ranking;
    }

    public function setRanking(?string $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'birthday' => $this->birthday,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'lastLogAt' => $this->lastLogAt,
            'team' => $this->team,
            'ranking' => $this->ranking,
            'roles' => $this->roles,
        ];
    }

    public function __unserialize(array $serialized)
    {
        $this->id = $serialized['id'];
        $this->email = $serialized['email'];
        $this->password = $serialized['password'];
        $this->firstname = $serialized['firstname'];
        $this->lastname = $serialized['lastname'];
        $this->birthday = $serialized['birthday'];
        $this->createdAt = $serialized['createdAt'];
        $this->updatedAt = $serialized['updatedAt'];
        $this->lastLogAt = $serialized['lastLogAt'];
        $this->team = $serialized['team'];
        $this->ranking = $serialized['ranking'];
        $this->roles = $serialized['roles'];
        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenValidity(): ?\DateTimeInterface
    {
        return $this->tokenValidity;
    }

    public function setTokenValidity(?\DateTimeInterface $tokenValidity): self
    {
        $this->tokenValidity = $tokenValidity;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(\DateTimeInterface $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    /**
     * @return Collection|MatchList[]
     */
    public function getMatchLists(): Collection
    {
        return $this->matchLists;
    }

    public function addMatchList(MatchList $matchList): self
    {
        if (!$this->matchLists->contains($matchList)) {
            $this->matchLists[] = $matchList;
            $matchList->addUser($this);
        }

        return $this;
    }

    public function removeMatchList(MatchList $matchList): self
    {
        if ($this->matchLists->removeElement($matchList)) {
            $matchList->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Stage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->addUser($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            $stage->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|MatchCancellation[]
     */
    public function getMatchCancellations(): Collection
    {
        return $this->matchCancellations;
    }

    public function addMatchCancellation(MatchCancellation $matchCancellation): self
    {
        if (!$this->matchCancellations->contains($matchCancellation)) {
            $this->matchCancellations[] = $matchCancellation;
            $matchCancellation->setUser($this);
        }

        return $this;
    }

    public function removeMatchCancellation(MatchCancellation $matchCancellation): self
    {
        if ($this->matchCancellations->removeElement($matchCancellation)) {
            // set the owning side to null (unless already changed)
            if ($matchCancellation->getUser() === $this) {
                $matchCancellation->setUser(null);
            }
        }

        return $this;
    }

}