<?php

namespace App\Entity;

use App\Repository\MatchCancellationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MatchCancellationRepository::class)
 */
class MatchCancellation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="matchCancellations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=MatchList::class, inversedBy="matchCancellations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Matchs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 1,
     *     max= 255,
     *     minMessage= "Votre message doit avoir au moins {{ limit }} caractères",
     *     maxMessage= "Votre message ne peut avoir plus de {{ limit }} caractères"
     * )
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMatchs(): ?MatchList
    {
        return $this->Matchs;
    }

    public function setMatchs(?MatchList $Matchs): self
    {
        $this->Matchs = $Matchs;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
