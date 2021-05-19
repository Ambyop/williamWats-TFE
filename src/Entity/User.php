<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

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
     *     pattern="/^[a-zA-ZÀ-ÿœ']+$/",
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
     *     pattern="/^[a-zA-ZÀ-ÿœ]+$/",
     *     match=true,
     *     message="Merci de mettre uniquement que des lettres"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
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
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity=Teams::class, inversedBy="users")
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ranking;

    public function __construct()
    {
        $this->team = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Teams[]
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    public function addTeam(Teams $team): self
    {
        if (!$this->team->contains($team)) {
            $this->team[] = $team;
        }

        return $this;
    }

    public function removeTeam(Teams $team): self
    {
        $this->team->removeElement($team);

        return $this;
    }

    public function getRanking(): ?string
    {
        return $this->ranking;
    }

    public function setRanking(string $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }
}
