<?php

namespace App\Controller;

use App\Services\DiscordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(DiscordService $discordService)
    {
        
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);

    }

}
