<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentMatchRepository")
 */
class TournamentMatch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="tournamentMatches")
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentTeam", inversedBy="tournamentMatches")
     */
    private $opponentA;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentTeam", inversedBy="tournamentMatches")
     */
    private $opponentB;

    /**
     * @ORM\Column(type="integer")
     */
    private $blockId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentTeam", inversedBy="tournamentMatches")
     */
    private $winner;

    public function __construct()
    {
        $this->opponentA = new ArrayCollection();
        $this->opponentB = new ArrayCollection();
        $this->winner = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * @return Collection|TournamentTeam[]
     */
    public function getOpponentA(): Collection
    {
        return $this->opponentA;
    }

    public function addOpponentA(TournamentTeam $opponentA): self
    {
        if (!$this->opponentA->contains($opponentA)) {
            $this->opponentA[] = $opponentA;
        }

        return $this;
    }

    public function removeOpponentA(TournamentTeam $opponentA): self
    {
        if ($this->opponentA->contains($opponentA)) {
            $this->opponentA->removeElement($opponentA);
        }

        return $this;
    }

    /**
     * @return Collection|TournamentTeam[]
     */
    public function getOpponentB(): Collection
    {
        return $this->opponentB;
    }

    public function addOpponentB(TournamentTeam $opponentB): self
    {
        if (!$this->opponentB->contains($opponentB)) {
            $this->opponentB[] = $opponentB;
        }

        return $this;
    }

    public function removeOpponentB(TournamentTeam $opponentB): self
    {
        if ($this->opponentB->contains($opponentB)) {
            $this->opponentB->removeElement($opponentB);
        }

        return $this;
    }

    public function getBlockId(): ?int
    {
        return $this->blockId;
    }

    public function setBlockId(int $blockId): self
    {
        $this->blockId = $blockId;

        return $this;
    }

    /**
     * @return Collection|TournamentTeam[]
     */
    public function getWinner(): Collection
    {
        return $this->winner;
    }

    public function addWinner(TournamentTeam $winner): self
    {
        if (!$this->winner->contains($winner)) {
            $this->winner[] = $winner;
        }

        return $this;
    }

    public function removeWinner(TournamentTeam $winner): self
    {
        if ($this->winner->contains($winner)) {
            $this->winner->removeElement($winner);
        }

        return $this;
    }
}
