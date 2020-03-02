<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentModeRepository")
 */
class TournamentMode
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
     * @ORM\Column(type="integer")
     */
    private $teamSize;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxTeamsCount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tournament", mappedBy="mode")
     */
    private $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
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

    public function getTeamSize(): ?int
    {
        return $this->teamSize;
    }

    public function setTeamSize(int $teamSize): self
    {
        $this->teamSize = $teamSize;

        return $this;
    }

    public function getMaxTeamsCount(): ?int
    {
        return $this->maxTeamsCount;
    }

    public function setMaxTeamsCount(int $maxTeamsCount): self
    {
        $this->maxTeamsCount = $maxTeamsCount;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Tournament[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments[] = $tournament;
            $tournament->setMode($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->contains($tournament)) {
            $this->tournaments->removeElement($tournament);
            // set the owning side to null (unless already changed)
            if ($tournament->getMode() === $this) {
                $tournament->setMode(null);
            }
        }

        return $this;
    }
}
