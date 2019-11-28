<?php

namespace App\Controller;

use App\Services\DiscordService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponse;

class DiscordController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/login", name="login")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     *
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('discord')
            ->redirect(['identify', 'email'],
                []
            );
    }

    /**
     * After going to Discord, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/login/check", name="login_check")
     */
    public function connectCheckAction()
    {
        //
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return null;
    }
}