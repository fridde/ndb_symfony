<?php

namespace App\Controller;

use App\Entity\School;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[
    IsGranted('ROLE_CONFIRMED_USER'),
    IsGranted('edit', subject: 'school')
]
class SchoolPageController extends AbstractController
{
    #[Route(
        '/skola/{school}',
        name: 'school_overview',
        methods: ['GET']
    )]
    public function schoolOverview(School $school): Response
    {
        dump($school);
        return $this->render('base.html.twig');
    }


}
