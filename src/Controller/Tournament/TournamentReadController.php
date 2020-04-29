<?php

namespace App\Controller\Tournament;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TournamentReadController extends AbstractController
{
    /**
     * @Route("/tournament/read", name="tournament_read")
     */
    public function index(TournamentRepository $tournamentRepository)
    {
        $tournaments = $tournamentRepository->findAll();

        return $this->render('tournament/tournamentView.html.twig', [
            'title' => 'Turniere',
            'tournaments' => $tournaments
        ]);
    }
}
