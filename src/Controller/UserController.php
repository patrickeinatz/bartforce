<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\DiscordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @param $memberId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/profile/{discordId}", name="user_profile")
     */
    public function userProfileView(DiscordService $discordService, UserRepository $userRepository, string $discordId)
    {
        $profileUser = $userRepository->findOneBy(['discordId' => $discordId]);
        $profileUserRoles = [];

        $memberData = $discordService->getMemberData($discordId);
        $roles = $discordService->getGuildRoles();

        foreach ($roles as $role)
        {
            foreach ($memberData->roles as $memberRole){
                if($role->id == $memberRole){
                    $set = [
                        'name' => $role->name,
                        'color' => '#'.dechex($role->color)
                    ];

                    array_push($profileUserRoles, $set);
                }
            }
        }

        return $this->render('user/profile.html.twig', [
            'profileUser' => $profileUser,
            'member' => $memberData,
            'profileUserRoles' => $profileUserRoles
        ]);

    }
}
