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

        $userGivenKudos = count($profileUser->getTopicKudos()) + count($profileUser->getPostKudos());
        $userReceivedKudos = 0;

        $userTopics = $profileUser->getForumTopics();
        $userPosts = $profileUser->getForumPosts();

        foreach ($userTopics as $topic){
            $userReceivedKudos += count($topic->getTopicKudos());
        }
        foreach ($userPosts as $post){
            $userReceivedKudos += count($post->getPostKudos());
        }

        return $this->render('user/profile.html.twig', [
            'profileUser' => $profileUser,
            'member' => $memberData,
            'profileUserRoles' => $profileUserRoles,
            'userGivenKudos' => $userGivenKudos,
            'userReceivedKudos' => $userReceivedKudos
        ]);

    }

    /**
     * @Route("/memberlist", name="memberlist")
     */
    public function memberlist(DiscordService $discordService, UserRepository $userRepository)
    {
        $signedMembers = $userRepository->findAll();
        $discordMembers = $discordService->getMemberList();

        return $this->render('user/memberlist.html.twig', [
            'signedMembers' => $signedMembers,
            'discordMembers' => sizeof($discordMembers)
        ]);

    }
}

