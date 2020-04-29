<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 */
class Tournament
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentMode", inversedBy="tournaments")
     */
    private $mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tournaments")
     */
    private $champion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentTeam", mappedBy="tournament")
     */
    private $tournamentTeams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentMatch", mappedBy="tournament")
     */
    private $tournamentMatches;

    /**
     * @ORM\Column(type="boolean")
     */
    private $openQualifying;

    public function __construct()
    {
        $this->tournamentTeams = new ArrayCollection();
        $this->tournamentMatches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMode(): ?TournamentMode
    {
        return $this->mode;
    }

    public function setMode(?TournamentMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getChampion(): ?User
    {
        return $this->champion;
    }

    public function setChampion(?User $champion): self
    {
        $this->champion = $champion;

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

    /**
     * @return Collection|TournamentTeam[]
     */
    public function getTournamentTeams(): Collection
    {
        return $this->tournamentTeams;
    }

    public function addTournamentTeam(TournamentTeam $tournamentTeam): self
    {
        if (!$this->tournamentTeams->contains($tournamentTeam)) {
            $this->tournamentTeams[] = $tournamentTeam;
            $tournamentTeam->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentTeam(TournamentTeam $tournamentTeam): self
    {
        if ($this->tournamentTeams->contains($tournamentTeam)) {
            $this->tournamentTeams->removeElement($tournamentTeam);
            // set the owning side to null (unless already changed)
            if ($tournamentTeam->getTournament() === $this) {
                $tournamentTeam->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TournamentMatch[]
     */
    public function getTournamentMatches(): Collection
    {
        return $this->tournamentMatches;
    }

    public function addTournamentMatch(TournamentMatch $tournamentMatch): self
    {
        if (!$this->tournamentMatches->contains($tournamentMatch)) {
            $this->tournamentMatches[] = $tournamentMatch;
            $tournamentMatch->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentMatch(TournamentMatch $tournamentMatch): self
    {
        if ($this->tournamentMatches->contains($tournamentMatch)) {
            $this->tournamentMatches->removeElement($tournamentMatch);
            // set the owning side to null (unless already changed)
            if ($tournamentMatch->getTournament() === $this) {
                $tournamentMatch->setTournament(null);
            }
        }

        return $this;
    }

    public function getOpenQualifying(): ?bool
    {
        return $this->openQualifying;
    }

    public function setOpenQualifying(bool $openQualifying): self
    {
        $this->openQualifying = $openQualifying;

        return $this;
    }
}
