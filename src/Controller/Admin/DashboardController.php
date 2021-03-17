<?php

namespace App\Controller\Admin;

use App\Entity\CalendarEvent;
use App\Entity\Note;
use App\Entity\School;
use App\Entity\Topic;
use App\Entity\User;
use App\Entity\Visit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ndb Symfony');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::linkToCrud('Users', 'fa fa-tags', User::class),
            MenuItem::linkToCrud('Visits', 'fa fa-tags', Visit::class),
            MenuItem::linkToCrud('Topics', 'fa fa-tags', Topic::class),
            MenuItem::linkToCrud('Schools', 'fa fa-tags', School::class),
            MenuItem::linkToCrud('Notes', 'fa fa-tags', Note::class),
            MenuItem::linkToCrud('CalendarEvents', 'fa fa-tags', CalendarEvent::class),
        ];
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(200)
            ->setDateFormat('YYYY-MM-dd')
            ->setDateTimeFormat('YYYY-MM-dd HH:mm')
            ;
    }
}
