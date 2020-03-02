<?php

namespace App\Security;

use App\Entity\User as User;
use App\Services\DiscordService;
use App\Services\UserProfileService;
use Symfony\Component\Security\Core\User\UserInterface as UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\DiscordServer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DiscordAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $discordService;
    private $userProfileService;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, DiscordService $discordService, UserProfileService $userProfileService)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->discordService = $discordService;
        $this->userProfileService = $userProfileService;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'login_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getDiscordClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|object|UserInterface|null
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var $discordUser */
        $discordUser = $this->getDiscordClient()->fetchUserFromToken($credentials);

        $extendedUserData = $this->discordService->getMemberData($discordUser->getId());

        $discordUserRoles = [];

        foreach($extendedUserData->roles as $role)
        {
            array_push($discordUserRoles, $this->discordService->getGuildRoleByRoleId($role));
        }

        /** @var User $existingUser */
        $existingUser = $this->em->getRepository(User::class)->findOneBy(['discordId' => $discordUser->getId()]);

        if ($existingUser) {

            if(!$this->discordService->compareUserDataSets($existingUser, $discordUser)){
                $existingUser->setUsername($discordUser->getUsername());
                $existingUser->setEmail($discordUser->getEmail());
                $existingUser->setAvatar(
                    $this->discordService->getAvatarPath(
                        $discordUser->getId(),
                        $discordUser->getAvatarHash()
                    )
                );
            }

            if($existingUser->getScore() === NULL)
            {
                $existingUser->setScore(
                    $this->userProfileService->initScore($existingUser)
                );
            }

            if($this->userProfileService->daysSinceLastLogin($existingUser) > 1){
                $this->userProfileService->increaseScore($existingUser, 'login');
            }


            $existingUser->setLastLogin(new \DateTime());
            $existingUser->setRoles($discordUserRoles);
            $this->em->persist($existingUser);
            $this->em->flush();

            return $existingUser;

        } else {

            $user = new User();
            $user->setDiscordId($discordUser->getId());
            $user->setUsername($discordUser->getUsername());
            $user->setEmail($discordUser->getEmail());
            $user->setDiscriminator($discordUser->getDiscriminator());
            $user->setAvatar(
                $this->discordService->getAvatarPath(
                    $discordUser->getId(),
                    $discordUser->getAvatarHash()
                )
            );
            $user->setRoles($discordUserRoles);
            $user->setCreatedAt(new \DateTime());
            $user->setLastLogin(new \DateTime());
            $user->setJoinedAt($extendedUserData->joined_at);
            $user->setScore(
                    $this->userProfileService->initScore($user)
            );
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }
    }

    /**
     * @return DiscordServer
     */
    private function getDiscordClient()
    {
        /**
         * @var DiscordServer
         */
        return $this->clientRegistry
            ->getClient('discord');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        $targetUrl = $this->router->generate('index');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/login', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

}