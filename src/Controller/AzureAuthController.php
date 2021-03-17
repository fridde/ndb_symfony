<?php


namespace App\Controller;

use App\Security\AuthenticationUtils as Auth;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AzureAuthController extends AbstractController
{
    /**
     * @Route("/connect/azure", name="connect_via_azure")
     * */
    public function connectToAzure(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('azure')->redirect([], []);
    }

    /**
     * @Route("/connect/azure/check", name="connect_azure_check")
     * */
    public function checkAzureLogin(ClientRegistry $clientRegistry): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): Response
    {
        $response = new Response();
        $response->headers->clearCookie(Auth::COOKIE_KEY_NAME);

        return $this->render('base', [], $response);
    }



}