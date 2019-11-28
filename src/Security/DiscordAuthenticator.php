<?php

namespace App\Security;

use App\Entity\User as User;
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

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
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
        $discordUser = $this->getDiscordClient()->fetchUserFromToken($credentials);
        $email = $discordUser->getEmail();

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return $existingUser;
        } else {
            $user = new User();
            $user->setUsername($discordUser->getUsername());
            $user->setEmail($discordUser->getEmail());
            $user->setAvatarHash($discordUser->getAvatarHash());
            $user->setDiscordId($discordUser->getId());
            $user->setDiscriminator($discordUser->getDiscriminator());
            $user->setCreatedAt(new \DateTime());

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
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

}