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

        //WEBHOOK URL: https://discordapp.com/api/webhooks/669948357573083137/3fSvHYE2fdUIidb_tbA3jReIcsk-vwrGmGQvv5B9JaXAWDBrn2tPrGVerze9Kkg_gJq5
        /*dd($discordService->getDiscordServer()->webhook->executeWebhook([
            'webhook.id' => 669948357573083137,
            'webhook.token' => '3fSvHYE2fdUIidb_tbA3jReIcsk-vwrGmGQvv5B9JaXAWDBrn2tPrGVerze9Kkg_gJq5',
            'content' => 'Hello Peoples! Me is Watson.'
        ]));
        */


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);

    }

}
