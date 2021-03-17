<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Security\AuthenticationUtils as Auth;

class AuthKeyAuthenticator extends AbstractGuardAuthenticator
{
    private Auth $auth;
    private UrlGeneratorInterface $router;

    public function __construct(
        Auth $auth,
        UrlGeneratorInterface $router
    )
    {
        $this->auth = $auth;
        $this->router = $router;
    }


    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $request->getSession()->set('request_url', $request->getUri());
        $url = $this->router->generate('connect_via_azure');

        return new RedirectResponse($url, Response::HTTP_TEMPORARY_REDIRECT);
    }

    public function supports(Request $request): bool
    {
        return $request->cookies->has(Auth::COOKIE_KEY_NAME);
    }

    public function getCredentials(Request $request): string
    {
        return $request->cookies->get(Auth::COOKIE_KEY_NAME);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        return $this->auth->getUserFromAuthKey($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->auth->authKeyMatchesUser($user, $credentials);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}