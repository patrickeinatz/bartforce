<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentTeamRepository")
 */
class TournamentTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tournament", inversedBy="tournamentTeams")
     */
    private $tournament;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentParticipant", mappedBy="team")
     */
    private $tournamentParticipants;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TournamentMatch", mappedBy="opponeopponentA")
     */
    private $tournamentMatches;


    public function __construct()
    {
        $this->tournamentParticipants = new ArrayCollection();
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

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTournament(): ?tournament
    {
        return $this->tournament;
    }

    public function setTournament(?tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * @return Collection|TournamentParticipant[]
     */
    public function getTournamentParticipants(): Collection
    {
        return $this->tournamentParticipants;
    }

    public function addTournamentParticipant(TournamentParticipant $tournamentParticipant): self
    {
        if (!$this->tournamentParticipants->contains($tournamentParticipant)) {
            $this->tournamentParticipants[] = $tournamentParticipant;
            $tournamentParticipant->setTeam($this);
        }

        return $this;
    }

    public function removeTournamentParticipant(TournamentParticipant $tournamentParticipant): self
    {
        if ($this->tournamentParticipants->contains($tournamentParticipant)) {
            $this->tournamentParticipants->removeElement($tournamentParticipant);
            // set the owning side to null (unless already changed)
            if ($tournamentParticipant->getTeam() === $this) {
                $tournamentParticipant->setTeam(null);
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
            $tournamentMatch->addOpponeopponentA($this);
        }

        return $this;
    }

    public function removeTournamentMatch(TournamentMatch $tournamentMatch): self
    {
        if ($this->tournamentMatches->contains($tournamentMatch)) {
            $this->tournamentMatches->removeElement($tournamentMatch);
            $tournamentMatch->removeOpponeopponentA($this);
        }

        return $this;
    }
}
