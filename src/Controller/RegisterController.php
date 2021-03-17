<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{

    private SessionInterface $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Route("/register", name="register")
     */
    public function registerForm(): Response
    {
        $user_data = json_decode($this->session->get('user_data'), true);
        dump($user_data);
        return $this->render('base.html.twig');
    }



}