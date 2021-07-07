<?php


namespace App\Utils;


use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Calendar
{
    public \Google_Client $client;
    public \Google_Service_Calendar $calendar;
    public EntityManagerInterface $em;
    private array $config = [
        'type' => 'service_account',
        'project_id' => 'naturskolans-databas',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs'
    ];
    private string $calendar_id;

    public function __construct(
        EntityManagerInterface $em,
        $google_private_key_id,
        $google_private_key,
        $google_client_email,
        $google_client_id,
        $google_client_x_509_cert_url,
        $google_calendar_id
    )
    {
        $this->calendar_id = $google_calendar_id;

        $this->em = $em;
        $this->client = new \Google_Client();


        $this->config['private_key_id'] = $google_private_key_id;
        $this->config['private_key'] = $google_private_key;
        $this->config['client_email'] = $google_client_email;
        $this->config['client_id'] = $google_client_id;
        $this->config['client_x509_cert_url'] = $google_client_x_509_cert_url;

        $this->client->setAuthConfig($this->config);
        $this->client->addScope(\Google_Service_Calendar::CALENDAR);
        $this->calendar = new \Google_Service_Calendar($this->client);
    }

    public function insertEvent(): void
    {
        /** @var Visit $visit */
        $visit = $this->em->getRepository(Visit::class)->find(12);
        $event_id = $this->createEventId($visit);

        $event = new \Google_Service_Calendar_Event();

        $event->setId($event_id);
        $event->setSummary('Bigger event');
        $start = new \Google_Service_Calendar_EventDateTime();
        $end = new \Google_Service_Calendar_EventDateTime();
        $start->setDateTime('2021-06-28T16:00:00');
        $end->setDateTime('2021-06-28T19:00:00');

        $start->setTimeZone('Europe/Stockholm');
        $end->setTimeZone('Europe/Stockholm');

        $event->setStart($start);
        $event->setEnd($end);
        $this->calendar->events->insert($this->calendar_id, $event);

    }

    public function updateEvent(): void
    {
        /** @var Visit $visit */
        $visit = $this->em->getRepository(Visit::class)->find(10);
        $event_id = $this->createEventId($visit);

        $event = $this->calendar->events->get($this->calendar_id, $event_id);
        $event->setSummary((string)$visit);

        $this->calendar->events->update($this->calendar_id, $event_id, $event);

    }

    public function createEventId($event): string
    {
        $number = 0;
        $first_letter = '';

        switch (get_class($event)) {
            case Visit::class:
                /** @var Visit $event */
                $number = str_pad($event->getId(), 8, '0', STR_PAD_LEFT);
                $first_letter = 'v';
                break;
            default:
                break;
        }
        $first_letter_base32 = (string)base_convert(ord($first_letter), 10, 32);

        return $first_letter_base32 . $number;
    }

}