<?php


namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class AzureAuthenticator extends AbstractGuardAuthenticator
{
    private EntityManagerInterface $em;
    private AuthenticationUtils $auth;
    private ClientRegistry $client_registry;
    private UrlGeneratorInterface $router;

    private array $user_data;

    public function __construct(
        EntityManagerInterface $em,
        AuthenticationUtils $auth,
        ClientRegistry $clientRegistry,
        UrlGeneratorInterface $router

    )
    {
        $this->em = $em;
        $this->auth = $auth;
        $this->client_registry = $clientRegistry;
        $this->router = $router;
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
    }


    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_azure_check';
    }


    public function getCredentials(Request $request): ?string
    {
        $client = $this->client_registry->getClient('azure');
        $provider = $client->getOAuth2Provider();

        try {
            $token = $client->getAccessToken();
            $potential_user_data = $provider->get('me', $token);

            $this->setRelevantUserData($potential_user_data);

        } catch (IdentityProviderException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return $this->user_data['mail'] ?? null;
    }


    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {

        return $userProvider->loadUserByUsername($credentials);
    }


    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $credentials === $user->getUsername(); // getUsername returns the mailaddress
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if(!($exception instanceof UsernameNotFoundException)){
            return new Response($exception->getMessage());
        }

        $request->getSession()->set('user_data', json_encode($this->user_data));
        $url = $this->router->generate('register');

        return new RedirectResponse($url, Response::HTTP_TEMPORARY_REDIRECT);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): Response
    {
        $user = $token->getUser();
        /** @var User $user */
        $this->addUserDataIfAvailable($user);

        $cookie = $this->auth->createCookieWithAuthKey($user);

        $response = new RedirectResponse($request->getSession()->get('request_url'));
        $response->headers->setCookie($cookie);
        return $response;
    }


    public function supportsRememberMe(): bool
    {
        return false;
    }

    private function setRelevantUserData(array $potential_user_data): void
    {
        $pud = $potential_user_data;
        $user_data = [
            'mail' => $pud['mail'] ?? null,
            'mobile' => $pud['mobile'] ?? null,
            'first_name' => $pud['givenName'] ?? null,
            'last_name' => $pud['surname'] ?? null
        ];

        $this->user_data = array_filter($user_data);
    }

    private function addUserDataIfAvailable(User $user): void
    {
        $mobil = $this->user_data['mobile'] ?? null;
        if (!empty($mobil) && !$user->hasMobil()) {
            $user->setMobil($mobil);
        }

        $first = $this->user_data['first_name'] ?? null;
        if (!empty($first) && empty($user->getFirstName())) {
            $user->setFirstName($first);
        }

        $last = $this->user_data['last_name'] ?? null;
        if (!empty($last) && empty($user->getLastName())) {
            $user->setLastName($last);
        }

        $this->em->persist($user);
        $this->em->flush();
    }

}