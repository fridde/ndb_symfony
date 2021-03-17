<?php

namespace App\Repository;

use App\Entity\School;
use Doctrine\ORM\EntityRepository;

class SchoolRepository extends EntityRepository
{

    public function getActiveSchools(): array
    {
        return $this->findBy(['Status' => 1]);
    }

    public function getSchoolLabels(): array
    {
        $labels = [];

        $schools = $this->getActiveSchools();
        foreach($schools as $school){
            /** @var School $school  */
            $labels[$school->getName()] = $school->getId();
        }

        return $labels;
    }


}