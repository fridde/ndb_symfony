<?php


namespace App\Controller;


use App\Controller\Admin\DashboardController;
use App\Entity\User;
use App\Security\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[
        Route('/', name: 'index'),
        IsGranted('ROLE_CONFIRMED_USER')
    ]
    public function enter(): Response
    {
        $user = $this->getUser();
        if($user instanceof User){
            if($user->isPending()){
                // TODO: this should render an info page that the registration is still ongoing
                dump('This should show the *pending user* page');
                return $this->render('base.html.twig');
            }
            if($this->isGranted(User::convertRoleToString(User::ROLE_ADMIN))){
                return $this->forward(DashboardController::class . '::index');
            }
            return $this->forward(SchoolPageController::class . '::schoolOverview',
                ['school' => $user->getSchool()]
            );
        }



        // TODO: this should render some descriptive page only for visitors
        dump('This should show the *visiting anonymous* page');
        return $this->render('base.html.twig');

    }

}