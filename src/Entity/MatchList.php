<?php

namespace App\Entity;

use App\Repository\MatchListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchListRepository::class)
 */
class MatchList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="matchLists")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=MatchCancellation::class, mappedBy="Matchs")
     */
    private $matchCancellations;

    /**
     * @ORM\ManyToOne(targetEntity=ClubLists::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->matchCancellations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $matchCancellation->setMatchs($this);
        }

        return $this;
    }

    public function removeMatchCancellation(MatchCancellation $matchCancellation): self
    {
        if ($this->matchCancellations->removeElement($matchCancellation)) {
            // set the owning side to null (unless already changed)
            if ($matchCancellation->getMatchs() === $this) {
                $matchCancellation->setMatchs(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?ClubLists
    {
        return $this->location;
    }

    public function setLocation(?ClubLists $location): self
    {
        $this->location = $location;

        return $this;
    }
}
