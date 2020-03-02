<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentParticipantRepository")
 */
class TournamentParticipant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament")
     */
    private $Tournament;

    /**
     * @ORM\Column(type="integer")
     */
    private $wins;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentTeam", inversedBy="tournamentParticipant")
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->Tournament;
    }

    public function setTournament(?Tournament $Tournament): self
    {
        $this->Tournament = $Tournament;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getTeam(): ?TournamentTeam
    {
        return $this->team;
    }

    public function setTeam(?TournamentTeam $team): self
    {
        $this->team = $team;

        return $this;
    }
}
