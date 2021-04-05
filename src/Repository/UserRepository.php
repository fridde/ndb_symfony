<?php

namespace App\Repository;

use App\Utils\Misc;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class UserRepository extends EntityRepository
{
    use Filterable;

    public array $filter_translator = [
        'active' => 'isActive'
    ];

    /** @noinspection NullPointerExceptionInspection */
    public function isActive(bool $active): void
    {
        $status = (int) $active;
        $exp = Criteria::expr()->eq('Status', $status);
        $this->criteria->andWhere($exp);
    }



}