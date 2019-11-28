<?php

namespace App\Controller;

use App\Services\DiscordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param $memberId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/profile/{userId}", name="user_profile")
     */
    public function userProfileView($userId, DiscordService $discordService)
    {
        return $this->render('user/profile.html.twig', [
            'member' => $discordService->getMemberData($userId),
            'roles' => $discordService->getGuildRoles()
        ]);

    }
}
