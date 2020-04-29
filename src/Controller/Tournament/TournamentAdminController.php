<?php

namespace App\Controller\Tournament;

use App\Form\TournamentModeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TournamentAdminController extends AbstractController
{
    /**
     * @Route("/tournament/admin", name="tournament_admin")
     */
    public function index()
    {
        return $this->render('tournament/tournamentAdmin.html.twig', [
            'title' => 'Turniere (Administration)',
        ]);
    }

    public function createTournamentMode(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(TournamentModeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('tournament/tournamentMode.html.twig', [
            'title' => 'Neuer Turnier-Modus',
        ]);

    }
}
