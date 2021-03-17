<?php

namespace App\DataFixtures;

use App\Entity\CalendarEvent;
use App\Entity\Change;
use App\Entity\Group;
use App\Entity\Location;
use App\Entity\Message;
use App\Entity\Note;
use App\Entity\School;
use App\Entity\SystemStatus;
use App\Entity\Topic;
use App\Entity\User;
use App\Entity\Visit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class AppFixtures extends Fixture
{
    private ObjectManager $om;

    private static array $special_cases = [
        'CalendarEvent' => ['IsAllDay'],
    ];

    private static array $convert_to_entity = [
        School::class,
        Location::class,
        User::class,
        Group::class,
        Topic::class,
        Visit::class
        ];

    private static array $convert_to_date = [
        'StartDateTime', 'EndDateTime', 'Date', 'LastUpdate'
    ];

    private array $short_to_long = [];


    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void

    {
        $this->om = $manager;

        $reader = IOFactory::createReader('Ods');
        $reader->setReadDataOnly(true);
        $workbook = $reader->load(__DIR__ . '/test_data_ndbsymfony.ods');
        $sheets = $workbook->getAllSheets();

        foreach ($sheets as $sheet) {
            $title = $sheet->getTitle();
            if (self::isIgnored($title)) {
                continue;
            }
            $rows = $sheet->toArray();
            $headers = array_shift($rows);
            $rows = array_map(fn($r) => array_combine($headers, $r), $rows);

            foreach ($rows as $row) {
                $create_method = 'create' . $title;
                $entity = $this->$create_method($row);

                $special_cases = self::$special_cases[$title] ?? [];
                $this->setStandardValues($entity, $row, $special_cases);

                $this->om->persist($entity);

                $metadata = $this->om->getClassMetaData(get_class($entity));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->om->flush();

        }

    }


    private function createSystemStatus(array $row): SystemStatus
    {
        return new SystemStatus();
    }


    private function createChange(array $row): Change
    {
        return new Change();
    }


    private function createCalendarEvent(array $row): CalendarEvent
    {
        $e = new CalendarEvent();

        $e->setIsAllDay((bool)$row['IsAllDay']);

        return $e;
    }

    private function createSchool(array $row): School
    {
        return new School();
    }

    private function createLocation(array $row): Location
    {
        return new Location();
    }

    private function createUser(array $row): User
    {
        return new User();
    }

    private function createTopic(array $row): Topic
    {
        return new Topic();
    }

    private function createGroup(array $row): Group
    {
        return new Group();
    }

    private function createMessage(array $row): Message
    {
        return new Message();
    }

    private function createVisit(array $row): Visit
    {
        return new Visit();
    }

    private function createNote(array $row): Note
    {
        return new Note();
    }

    private function setStandardValues($object, $row, $exceptions = []): void
    {
        $this->setShortToLongArray();
        $short_names = array_keys($this->short_to_long);

        foreach ($row as $header => $value) {
            if (self::isIgnored($header) || in_array($header, $exceptions, true)){
                continue;
            }
            if(in_array($header, $short_names, true)){
                $repo = $this->om->getRepository($this->short_to_long[$header]);
                $value = $repo->find($value);
            }
            if(in_array($header, self::$convert_to_date, true)){
                $value = empty($value) ? null : new \DateTime($value);
            }
            $setter_method = 'set' . ucfirst($header);
            $object->$setter_method($value);
        }
    }

    private static function isIgnored(string $field_or_title): bool
    {
        return strpos($field_or_title, '_ignore') !== false;
    }

    private function setShortToLongArray(): void
    {
        if(!empty($this->short_to_long)){
            return;
        }
        foreach(self::$convert_to_entity as $val){
            $key = (new \ReflectionClass($val))->getShortName();
            $this->short_to_long[$key] = $val;
        }
    }


}
