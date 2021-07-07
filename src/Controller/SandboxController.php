<?php


namespace App\Controller;


use App\Settings;
use App\Utils\Calendar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class SandboxController extends AbstractController
{

    /**
     * @Route("/sandbox")
     */
    public function runSandbox(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction'
        ]);

        $output = new NullOutput();
        $application->run($input, $output);

        return new Response();
    }

    /**
     * @Route("/sandbox/standard")
     */
    public function showStandardPage(Settings $settings): Response
    {
        $a = $settings->get('admin_summary.soon_last_visit');
        return $this->render('base.html.twig');
    }

    #[Route(
        '/sandbox/update'
    )]
    public function testUpdate(): Response
    {
        return $this->render('tests.html.twig');
    }

    #[Route(
        'sandbox/calendar'
    )]
    public function testCalendar(Calendar $calendar)
    {
        $calendar->insertEvent();
        return $this->render('base.html.twig');
    }
}