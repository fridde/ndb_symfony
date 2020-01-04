<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{

    /**
     * @Route("/skola/{school}")
     */
    public function homepage(string $school)
    {
        $school = $school ?? 'none';
        return $this->render('school/show_school.twig', ['school' => $school]);
    }

}